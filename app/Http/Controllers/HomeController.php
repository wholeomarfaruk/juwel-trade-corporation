<?php

namespace App\Http\Controllers;

use App\CAPI\PageViewEvent;
use App\CAPI\ViewItemEvent;
use App\Jobs\SendMetaCapiEventJob;
use App\Meta\MetaBaseData;
use App\Models\AreaKeyword;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Device;
use Illuminate\Http\Request;
use App\Models\products;
use App\Models\delivery_areas;
use App\Models\Slide;
use App\Models\Analytic;
use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use stdClass;
use App\Meta\PurchaseEvent;
use App\Support\DataLayer;


class HomeController extends Controller
{
    protected $pixelId;
    protected $accessToken;
    protected $test_event_code;
    protected $pixel_debug_mode = false;
    protected $client;

    public function __construct()
    {
        $this->pixelId         = config('conversionapi.meta_pixel_id');
        $this->accessToken     = config('conversionapi.meta_access_token');
        $this->test_event_code = config('conversionapi.meta_test_code');
    }

    public function index()
    {

        if (request()->boolean('debug_meta')) {
            $metaBaseData = new MetaBaseData();

            return response()->json([
                'debug' => true,
                'route' => 'home',
                'request' => [
                    'url' => request()->fullUrl(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->header('User-Agent'),
                    'cookies' => [
                        '_fbc' => request()->cookie('_fbc'),
                        '_fbp' => request()->cookie('_fbp'),
                        '_sfdid' => request()->cookie('_sfdid'),
                        '_sfud' => request()->cookie('_sfud'),
                    ],
                ],
                'meta_base_data' => $metaBaseData->toArray(),
            ]);
        }

        // Phase 1: render the JTC storefront design with demo data.
        // Phase 2 will map the real $products / $categories / $slides above
        // into the same array shape (see storeData()).
        return view('storefront.index', $this->storeData());
    }

    /** Tiny placeholder-image helper (swap for real product image URLs in Phase 2). */
    private function img(string $seed, int $size = 600): string
    {
        return "https://picsum.photos/seed/{$seed}/{$size}/{$size}";
    }

    /** Demo catalogue data shaped for the JTC storefront design. */
    private function storeData(): array
    {
        $categories = \App\Support\StorefrontData::categories();

        $products = [
            ['id' => 1,  'name' => 'Tynor Knee Support Sportif Neoprene J09', 'sku' => 'J09',      'image' => $this->img('p-knee'),      'price' => 1195, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Tynor',      'category' => 'orthopedic',       'badge' => null,   'tags' => ['best', 'browse']],
            ['id' => 2,  'name' => 'ALPK2 Aneroid Sphygmomanometer',         'sku' => '32507329', 'image' => $this->img('p-bp'),        'price' => 1499, 'compare_at_price' => 1860, 'price_max' => null, 'brand' => 'ALPK2',      'category' => 'medical-devices',  'badge' => 'deal', 'tags' => ['deal', 'browse']],
            ['id' => 3,  'name' => 'Adjustable Bed Backrest Support',         'sku' => '57600967', 'image' => $this->img('p-backrest'),  'price' => 1895, 'compare_at_price' => 3025, 'price_max' => null, 'brand' => 'Samson',     'category' => 'orthopedic',       'badge' => 'deal', 'tags' => ['deal', 'best']],
            ['id' => 4,  'name' => 'Fascia Gun Light Age 9-Head Massager',    'sku' => '16053546', 'image' => $this->img('p-massagegun'),'price' => 960,  'compare_at_price' => 1850, 'price_max' => null, 'brand' => 'Axon',       'category' => 'body-massager',    'badge' => 'deal', 'tags' => ['deal', 'best', 'browse']],
            ['id' => 5,  'name' => 'Human Knee Joint Anatomical Model',       'sku' => '38433051', 'image' => $this->img('p-model'),     'price' => 3100, 'compare_at_price' => 4695, 'price_max' => null, 'brand' => 'Samson',     'category' => 'anatomical-models','badge' => 'deal', 'tags' => ['deal']],
            ['id' => 6,  'name' => 'Digital Scale Bravo XT SS-05',            'sku' => '01544711', 'image' => $this->img('p-scale'),     'price' => 1799, 'compare_at_price' => 2120, 'price_max' => null, 'brand' => 'Camry',      'category' => 'lifestyle',        'badge' => 'deal', 'tags' => ['deal', 'scale']],
            ['id' => 7,  'name' => 'Infrared Rolling Massager',               'sku' => '70647533', 'image' => $this->img('p-infrared'),  'price' => 2000, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Axon',       'category' => 'physiotherapy',    'badge' => null,   'tags' => ['massager', 'browse']],
            ['id' => 8,  'name' => 'Jumper Digital Blood Pressure Machine',   'sku' => '03369693', 'image' => $this->img('p-bp2'),       'price' => 2350, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Jumper',     'category' => 'medical-devices',  'badge' => 'new',  'tags' => ['new']],
            ['id' => 9,  'name' => 'Procare Classic Steel Stethoscope',       'sku' => '03367755', 'image' => $this->img('p-steth'),     'price' => 1200, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Samson',     'category' => 'medical-devices',  'badge' => 'new',  'tags' => ['new', 'browse']],
            ['id' => 10, 'name' => 'Tiger Balm White Ointment 10g',           'sku' => '40617006', 'image' => $this->img('p-balm'),      'price' => 300,  'compare_at_price' => null, 'price_max' => null, 'brand' => 'Tiger Balm', 'category' => 'health-care',      'badge' => 'new',  'tags' => ['new', 'medicine']],
            ['id' => 11, 'name' => 'Donut Pillow Seat Cushion',               'sku' => '03365844', 'image' => $this->img('p-pillow'),    'price' => 420,  'compare_at_price' => null, 'price_max' => null, 'brand' => 'Tynor',      'category' => 'orthopedic',       'badge' => 'new',  'tags' => ['new']],
            ['id' => 12, 'name' => 'Portable Fingertip Pulse Oximeter LK87',  'sku' => '03352008', 'image' => $this->img('p-oxi'),       'price' => 400,  'compare_at_price' => null, 'price_max' => null, 'brand' => 'Beurer',     'category' => 'medical-devices',  'badge' => 'new',  'tags' => ['new']],
            ['id' => 13, 'name' => 'Tynor Wrist Brace With Thumb E06',        'sku' => '58705418', 'image' => $this->img('p-wrist'),     'price' => 400,  'compare_at_price' => null, 'price_max' => null, 'brand' => 'Tynor',      'category' => 'orthopedic',       'badge' => null,   'tags' => ['best', 'browse']],
            ['id' => 14, 'name' => 'Therapy TENS Machine Gel Pad',            'sku' => 'KF5050',   'image' => $this->img('p-gelpad'),    'price' => 135,  'compare_at_price' => null, 'price_max' => 215,  'brand' => 'Axon',       'category' => 'physiotherapy',    'badge' => null,   'tags' => ['best', 'browse']],
            ['id' => 15, 'name' => 'Heel Guard Pain Protector (Soft Gel)',    'sku' => '38553586', 'image' => $this->img('p-heel'),      'price' => 180,  'compare_at_price' => 350,  'price_max' => null, 'brand' => 'Tynor',      'category' => 'orthopedic',       'badge' => 'deal', 'tags' => ['deal', 'best']],
            ['id' => 16, 'name' => 'Tynor Posture Corrector A33',             'sku' => 'A33',      'image' => $this->img('p-posture'),   'price' => 1195, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Tynor',      'category' => 'orthopedic',       'badge' => null,   'tags' => ['browse', 'best']],
            ['id' => 17, 'name' => 'Premium Slimming Belt Massager',          'sku' => '01610657', 'image' => $this->img('p-slimbelt'),  'price' => 2400, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Axon',       'category' => 'body-massager',    'badge' => null,   'tags' => ['massager']],
            ['id' => 18, 'name' => 'Premium Hand Massager',                   'sku' => '01607123', 'image' => $this->img('p-handmass'),  'price' => 4000, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Axon',       'category' => 'body-massager',    'badge' => null,   'tags' => ['massager']],
            ['id' => 19, 'name' => 'Human Anatomy Chart',                     'sku' => 'HAC',      'image' => $this->img('p-chart'),     'price' => 499,  'compare_at_price' => 760,  'price_max' => null, 'brand' => 'Samson',     'category' => 'anatomical-models','badge' => 'deal', 'tags' => ['deal', 'model']],
            ['id' => 20, 'name' => 'Mini Human Skeleton Anatomy Model',       'sku' => '16559457', 'image' => $this->img('p-skeleton'),  'price' => 1850, 'compare_at_price' => 2255, 'price_max' => null, 'brand' => 'Samson',     'category' => 'anatomical-models','badge' => 'deal', 'tags' => ['deal', 'model']],
            ['id' => 21, 'name' => 'Camry Weight Scale 7009',                 'sku' => '01410632', 'image' => $this->img('p-scale2'),    'price' => 1300, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Camry',      'category' => 'lifestyle',        'badge' => null,   'tags' => ['scale', 'browse']],
            ['id' => 22, 'name' => 'Beurer Scale PS 240',                     'sku' => '01540550', 'image' => $this->img('p-scale3'),    'price' => 2900, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Beurer',     'category' => 'lifestyle',        'badge' => null,   'tags' => ['scale']],
            ['id' => 23, 'name' => 'Kinesiology Elastic Muscle Tape',         'sku' => '01556198', 'image' => $this->img('p-tape'),      'price' => 470,  'compare_at_price' => null, 'price_max' => null, 'brand' => 'Tynor',      'category' => 'gym-sports',       'badge' => null,   'tags' => ['browse', 'best']],
            ['id' => 24, 'name' => 'Nebulizer Machine Compact',               'sku' => '03361999', 'image' => $this->img('p-neb'),       'price' => 1650, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Dr. Trust',  'category' => 'medical-devices',  'badge' => null,   'tags' => ['browse']],
        ];

        $slides = [
            ['image' => $this->img('hero-ortho', 1200)],
            ['image' => $this->img('hero-physio', 1200)],
            ['image' => $this->img('hero-anatomy', 1200)],
        ];

        $promos = [
            ['image' => $this->img('promo-1', 700)],
            ['image' => $this->img('promo-2', 700)],
            ['image' => $this->img('promo-3', 700)],
        ];

        // Decorate each product with the display fields the card needs.
        $decorate = function (array $p): array {
            $isRange   = ! empty($p['price_max']);
            $isCompare = ! $isRange && ! empty($p['compare_at_price']);
            $pct       = $p['compare_at_price'] ? (int) round((1 - $p['price'] / $p['compare_at_price']) * 100) : 0;
            $money     = fn ($n) => '৳' . number_format((int) $n);

            return array_merge($p, [
                'showNew'        => $p['badge'] === 'new',
                'showDealPct'    => $p['badge'] === 'deal' && ! empty($p['compare_at_price']),
                'pctText'        => '-' . $pct . '%',
                'priceIsRange'   => $isRange,
                'priceIsCompare' => $isCompare,
                'priceSingle'    => ! $isRange && ! $isCompare,
                'priceText'      => $money($p['price']),
                'compareText'    => $p['compare_at_price'] ? $money($p['compare_at_price']) : '',
                'rangeText'      => $isRange ? $money($p['price']) . ' – ' . $money($p['price_max']) : '',
            ]);
        };

        $byTag = fn (string $t) => array_values(array_map($decorate, array_filter($products, fn ($p) => in_array($t, $p['tags'], true))));

        $discoverChips = [];
        foreach ($categories as $c) {
            foreach ($c['children'] as $child) {
                $discoverChips[] = $child;
            }
        }
        $discoverChips = array_slice($discoverChips, 0, 18);

        return [
            'categories'    => $categories,
            'slides'        => $slides,
            'promos'        => $promos,
            'heroBanners'   => array_slice($promos, 0, 2),
            'deals'         => $byTag('deal'),
            'bestSellers'   => $byTag('best'),
            'browseAll'     => array_values(array_map($decorate, array_slice($products, 0, 12))),
            'discoverChips' => $discoverChips,
            // Full catalogue for Alpine (add-to-cart / wishlist lookups by id).
            'productsJson'  => array_values(array_map($decorate, $products)),
        ];
    }

    public function shop()
    {

        $products = Products::where('status', 1)->orderByDesc('featured') // featured first
            ->orderByDesc('created_at')               // newest first
            ->paginate(12);
        $deliveryAreas = delivery_areas::all();
        $slides = Slide::all();
        $analytics = Analytic::all();
        $categories = Category::all();
        return view('shop', compact('products', 'deliveryAreas', 'slides', 'analytics', 'categories'));
    }
    public function categoryShow(Request $request, $slug)
    {

        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            abort(404);
        }

        $products = $category->products()->where('status', 1)->orderByDesc('featured') // featured first
            ->orderByDesc('created_at')               // newest first
            ->paginate(12);
        $segment = $category?->segments?->select('name')?->first() ? strtolower($category->segments->select('name')->first()['name']) : null;
        return view('category-products', compact('category', 'products', 'segment'));
    }
    public function SubcategoryShow(Request $request, $slug, $subslug)
    {
        $category = Category::where('slug', $subslug)->first();
        if (!$category) {
            abort(404);
        }

        $products = $category->products()->orderByDesc('featured') // featured first
            ->orderByDesc('created_at')               // newest first
            ->paginate(12);
        $segment = $category?->segments?->select('name')?->first() ? strtolower($category->segments->select('name')->first()['name']) : null;
        return view('category-products', compact('category', 'products', 'segment'));
    }


    public function productShow(Request $request, $segment, $slug, PageViewEvent $pageViewEvent)
    {


        $product = products::where('slug', $slug)->first();
        if (!$product) {
            abort(404);
        }
        $product->increment('views');

        $deliveryAreas = delivery_areas::limit(5)->get();
        $products = products::where('status', 1)->where('id', '!=', $product->id)->inRandomOrder()->limit(8)->get();
        $segment = $product?->segments?->select('name')?->first() ? strtolower($product->segments->select('name')->first()['name']) : null;
        $capi = new ViewItemEvent();
        $capi->push(
            null,
            currency: 'BDT',
            contentPrice: $product->price,
            contentId: $product->id,
            contentName: $product->name,
            contentType: 'product',
            contentCategory: $segment,
        );
        SendMetaCapiEventJob::dispatch($capi->serverPayload())->onQueue(env('META_CAPI_QUEUE', 'metacapi'));
        $viewItemEventPayload = $capi->browserEventPayload();
        return view('product-show', compact('product', 'deliveryAreas', 'products', 'segment', 'viewItemEventPayload'));
    }
    public function deviceRegister(Request $request)
    {

        // Variable initialization
        $phone = null;
        $name = null;
        $address = null;
        $fn = null;
        $ln = null;
        $udata_cookie = isset($_COOKIE['_sfud']) ? json_decode($_COOKIE['_sfud'], true) : null;
        $device_id_cookie = isset($_COOKIE['_sfdid']) ? $_COOKIE['_sfdid'] : null;
        $customer = null;
        $udata = null;
        $device_id = null;
        $email = null;
        $session_id = session()->getId();
        $session_id_cookie = isset($_COOKIE['_sfid']) ? $_COOKIE['_sfid'] : null;
        $visited_new_customer = "no";
        $visited_old_customer = "no";
        // Get device info
        $deviceInfo = $this->getDeviceInfo($request);
        $deviceInfo = $deviceInfo->getData(); // Extract data from JsonResponse
        $screen_size = $request->input('screen_size', null);
        $data = [
            'user_agent' => $deviceInfo->user_agent,
            'screen_size' => $screen_size,
        ];
        $data = json_encode($data);
        $device_id_hash = hash('sha256', $data);

        //filter number and name address
        if ($request->has('phone') && $request->input('phone') != null) {
            $rawPhone = $request->input('phone');
            // Keep only digits
            $phone = preg_replace('/\D/', '', $rawPhone);
            // Ensure it's last 11 digits
            $phone = substr($phone, -11);
            // If length < 11 and doesn't start with 0, fix it
            if (strlen($phone) === 10 && $phone[0] !== '0') {
                $phone = '0' . $phone;
            }
            if ($request->has('name') && $request->input('name') != null) {
                $rawName = $request->input('name');
                $name = filter_var($rawName, FILTER_SANITIZE_URL);
                $name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK);
                $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

                // Remove unwanted characters, allowing letters, spaces, hyphens, and apostrophes
                $name = preg_replace("/[^a-zA-Z-' ]/", '', $rawName);
                // Limit to 50 characters
                $name = substr($name, 0, 150);
                // Capitalize the first letter of each word
                $name = ucwords(strtolower($name));
                // Split into first and last name
                $nameParts = explode(' ', $name);
                $fn = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 0, -1)) : $nameParts[0]; // First name is all but last part
                $ln = count($nameParts) > 1 ? ($nameParts[count($nameParts) - 1]) : null; // Last name is the last part of the array end($nameParts);
            }
            //address
            if ($request->has('address') && $request->input('address') != null) {
                $rawAddress = $request->input('address');
                // Sanitize address
                $address = filter_var($rawAddress, FILTER_SANITIZE_URL);
                $address = filter_var($address, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK);
                $address = htmlspecialchars($address, ENT_QUOTES, 'UTF-8');
                $address = substr($rawAddress, 0, 512);
                // Capitalize the first letter of each word
                $address = ucwords(strtolower($address));
            }

        }
        if ($request->has('email') && $request->input('email') != null) {
            $rawEmail = $request->input('email');
            // Sanitize email
            $email = filter_var($rawEmail, FILTER_SANITIZE_EMAIL);
            // Validate email
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $email = null; // Invalid email
            }
        }
        //variable initialization end


        //_sfud Full work process
        //first check  cookie
        if (!$udata_cookie && $phone !== null && Str::length($phone) == 11) {
            $customer = $this->storeCustomer($fn, $ln, $email, $phone, $address);
            $udata = json_encode($customer);
            $visited_new_customer = "yes";
            setcookie('_sfud', $udata, time() + (86400 * 30 * 12), "/", '', true); // HttpOnly and Secure flags
            $device = Device::where('device_id', $device_id_cookie)->first();
            if ($device) {
                $device->customer_id = $customer->id;
                $device->save();
            }
        } elseif ($phone !== null && Str::length($phone) == 11 && $udata_cookie['phone'] == $phone) {

            $visited_old_customer = "yes";
            if (!isset($udata_cookie['first_name']) && $fn !== null) {
                $customer = $this->updateCustomer($fn, null, null, $phone, null);
                $udata = json_encode($customer);
                setcookie('_sfud', $udata, time() + (86400 * 30 * 12), "/", '', true); // HttpOnly and Secure flags

            }
            if (!isset($udata_cookie['last_name']) && $ln !== null) {
                $customer = $this->updateCustomer(null, $ln, null, $phone, null);
                $udata = json_encode($customer);
                setcookie('_sfud', $udata, time() + (86400 * 30 * 12), "/", '', true); // HttpOnly and Secure flags

            }
            if (!isset($udata_cookie['email']) && $email !== null) {
                $customer = $this->updateCustomer(null, null, $email, $phone, null);
                $udata = json_encode($customer);
                setcookie('_sfud', $udata, time() + (86400 * 30 * 12), "/", '', true); // HttpOnly and Secure flags
            }
            if (!isset($udata_cookie['address']) && $address !== null) {
                $customer = $this->updateCustomer(null, null, null, $phone, $address);
                $udata = json_encode($customer);
                setcookie('_sfud', $udata, time() + (86400 * 30 * 12), "/", '', true); // HttpOnly and Secure flags
            }
            if (!$customer) {
                $customer = Customer::where('phone', $phone)->first();
            }
        }
        if (isset($session_id) && $session_id != $session_id_cookie) {
            $device_data = Device::where('device_id', $device_id_cookie)->first();

            if ($device_data) {
                $udata = $device_data->customer;
                setcookie('_sfud', $udata, time() + (86400 * 30 * 12), "/", '', true); // HttpOnly and Secure flags

            }
            setcookie('_sfsid', $session_id, time() + (86400 * 1), "/", '', true); // HttpOnly and Secure flags
        }
        //end _sfud work process

        //device register process start




        if ($device_id_hash !== $device_id_cookie || !$device_id_cookie) {
            $device = Device::where('device_id', $device_id_hash)->first();
            if ($device) {
                if ($customer ?? false) {
                    $device->customer_id = $customer->id;
                }
                $device->last_activity = now();
                $device->save();
                setcookie('_sfdid', $device_id_hash, time() + (86400 * 30 * 12), "/", "", true); // HttpOnly and Secure flags
                return response()->json([
                    'message' => 'Device already registered.',
                    'device_id' => $device_id_hash,
                    'customer' => $customer,
                    'status' => 'exists',
                ]);
            } else {

                $diviceRegister = new Device();
                if ($customer ?? false) {
                    $diviceRegister->customer_id = $customer->id;
                }
                $diviceRegister->device_id = $device_id_hash;
                $diviceRegister->device_type = $deviceInfo->device_type;
                $diviceRegister->device_model = $deviceInfo->device_model;
                $diviceRegister->user_agent = $deviceInfo->user_agent;
                $diviceRegister->screen_size = $request->input('screen_size', null);
                $diviceRegister->last_activity = now();
                $diviceRegister->save();
                setcookie('_sfdid', $device_id_hash, time() + (86400 * 30 * 12), "/", "", true); // Secure flags

            }
        } elseif ($device_id_hash === $device_id_cookie) {
            $device = Device::where('device_id', $device_id_cookie)->first();

            if ($device) {
                if ($customer ?? false) {
                    $device->customer_id = $customer->id;
                }
                $device->last_activity = now();
                $device->save();
            } else {
                $diviceRegister = new Device();
                if ($customer ?? false) {
                    $diviceRegister->customer_id = $customer->id;
                }
                $diviceRegister->device_id = $device_id_hash;
                $diviceRegister->device_type = $deviceInfo->device_type;
                $diviceRegister->device_model = $deviceInfo->device_model;
                $diviceRegister->user_agent = $deviceInfo->user_agent;
                $diviceRegister->screen_size = $request->input('screen_size', null);
                $diviceRegister->last_activity = now();
                $diviceRegister->save();
                setcookie('_sfdid', $device_id_hash, time() + (86400 * 30 * 12), "/", "", true); // Secure flags
                return response()->json([
                    'message' => 'registered successfully in 2nd time.',
                    'device_id' => $device_id_hash,
                    'old_device_id' => $device_id_cookie,
                    'customer' => $customer,
                    'new_customer' => $visited_new_customer,
                    'old_customer' => $visited_old_customer,
                    'status' => 'exists'
                ]);
            }


        }


        return response()->json([
            'message' => 'Device registered successfully.',
            'device_id' => $device_id_hash,
            'customer' => $customer,
            'new_customer' => $visited_new_customer,
            'old_customer' => $visited_old_customer,
            'status' => 'success'
        ]);
    }
    public function getDeviceInfo(Request $request)
    {
        $agent = new Agent();

        // Default Device Type
        $deviceType = 'Desktop';

        if ($agent->isTablet()) {
            $deviceType = 'Tablet';
        } elseif ($agent->isMobile()) {
            $deviceType = 'Mobile';
        }

        // Extra check for Smart TV / Console (not fully supported by Agent, use userAgent)
        $userAgent = $request->header('User-Agent');
        if (stripos($userAgent, 'SMART-TV') !== false || stripos($userAgent, 'HbbTV') !== false) {
            $deviceType = 'TV';
        } elseif (stripos($userAgent, 'Xbox') !== false || stripos($userAgent, 'PlayStation') !== false) {
            $deviceType = 'Console';
        } elseif ($agent->isDesktop()) {
            $deviceType = 'Laptop/Desktop';
        }

        // Device Model (iPhone, Samsung, etc.)
        $deviceModel = $agent->device();

        // Browser/App Name
        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);

        // Detect special in-app browsers (Facebook, Instagram, etc.)
        $appName = $browser;
        if (stripos($userAgent, 'FBAN') !== false || stripos($userAgent, 'FBAV') !== false) {
            $appName = 'Facebook App';
        } elseif (stripos($userAgent, 'Instagram') !== false) {
            $appName = 'Instagram App';
        } elseif (stripos($userAgent, 'WhatsApp') !== false) {
            $appName = 'WhatsApp Browser';
        }

        // Platform (Windows, macOS, Android, iOS, etc.)
        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);

        return response()->json([
            'device_type' => $deviceType,
            'device_model' => $deviceModel,
            'browser_or_app' => $appName,
            'browser_version' => $browserVersion,
            'platform' => $platform,
            'platform_version' => $platformVersion,
            'user_agent' => $userAgent, // for debugging

        ]);
    }

    protected function storeCustomer($fn = null, $ln = null, $email = null, $phone = null, $address = null)
    {
        if ($phone == null) {
            return null;
        }
        $customer = Customer::where('phone', $phone)->first();
        if ($customer) {
            return $customer;
        }
        $customer = new Customer();
        $customer->phone = $phone;
        if ($fn != null && $fn != '') {
            $customer->first_name = $fn;
        }
        if ($ln != null && $ln != '') {
            $customer->last_name = $ln;
        }
        if ($email != null && $email != '') {
            $customer->email = $email;
        }
        if ($address != null && $address != '') {
            $customer->address = $address;
        }
        $customer->save();

        return $customer;
    }
    protected function updateCustomer($fn = null, $ln = null, $email = null, $phone = null, $address = null)
    {
        if ($phone == null) {
            return null;
        }
        $customer = Customer::where('phone', $phone)->first();
        if (!$customer) {
            return null;
        }
        if ($fn != null && $fn != '' && ($customer->first_name == null || empty($customer->first_name))) {
            $customer->first_name = $fn;
        }
        if ($ln != null && $ln != '' && ($customer->last_name == null || empty($customer->last_name))) {
            $customer->last_name = $ln;
        }
        if ($email != null && $email != '' && ($customer->email == null || empty($customer->email))) {
            $customer->email = $email;
        }
        if ($address != null && $address != '' && ($customer->address == null || empty($customer->address))) {
            $customer->address = $address;

        }
        if ($customer->city == null || $customer->state == null || $customer->country == null || $customer->zip_code == null) {
            $this->updateCustomerAddress($customer->id, $customer->address);
        }
        $customer->save();
        return $customer;
    }
    public static function updateCustomerAddress($customer_id, $address)
    {
        $customer = Customer::find($customer_id);
        if (!$customer) {
            return null;
        }

        if ($address == null || $address == '') {
            return null;
        }

        // Get all keywords from area_keywords table
        $keywords = AreaKeyword::pluck('name'); // ['dogair', 'demra', ...]

        // Find the first keyword that exists in the address
        $matchedKeyword = null;
        foreach ($keywords as $keyword) {
            if (stripos($address, $keyword) !== false) {
                $matchedKeyword = $keyword;
                break; // stop at first match
            }
        }

        if ($matchedKeyword) {
            // You can fetch the full area record if needed
            $area = AreaKeyword::where('name', $matchedKeyword)->first();

            // Optionally, save or update something in customer
            $customer->street = $area->name;
            $customer->zip_code = $area->zipcode->code ?? null;
            $customer->city = $area->zipcode->police_station->city->name ?? null;
            $customer->state = $area->zipcode->police_station->city->state->name ?? null;

            $customer->save();

            return $area; // return first matched area
        }

        return null; // no match found
    }
    public function fbPixelCAPI(Request $request)
    {

        // ignore_user_abort(true);
        // set_time_limit(0);

        // // Return early to frontend (optional)
        // response()->json(['status' => 'processing'])->send();
        // if (function_exists('fastcgi_finish_request')) {
        //     fastcgi_finish_request();
        // }

        $data = $request->all();
        // Log::info('FB Pixel CAPI Calling Started at ' . now());
        // Log::info('FB Pixel CAPI Data: ' . json_encode($data));
        try {
            $filterData = ['men', 'women', 'kids'];
            $segment = null;

            $segment = $request->segment ? strtolower($request->segment) : null;

            if ($request->event_name == 'page_view') {

                // $payload = [
                //     'data' => [
                //         [
                //             'event_name' => 'PageView',
                //             'action_source' => 'website',
                //             'event_time' => time(),
                //             'event_id' => $data['event_id'] ?? (string) Str::uuid(),
                //             'event_source_url' => !empty($data['event_source_url']) ? $data['event_source_url'] : $request->url(),
                //             'referrer_url' => !empty($data['referrer_url']) ? $data['referrer_url'] : $request->headers->get('referer'),
                //             'custom_data' => new stdClass,
                //             'user_data' => [
                //                 'client_user_agent' => $request->server('HTTP_USER_AGENT'),
                //                 'client_ip_address' => $request->ip(),
                //                 'fbp' => isset($_COOKIE['_fbp']) ? $_COOKIE['_fbp'] : null,
                //                 'fbc' => !empty($data['user_data']['fbc']) ? $data['user_data']['fbc'] : (isset($_COOKIE['custom_fbc']) ? $_COOKIE['custom_fbc'] : null),
                //                 'ph' => !empty($data['user_data']['phone_number']) ? $this->normalizeAndHash($data['user_data']['phone_number']) : null,
                //                 'fn' => !empty($data['user_data']['first_name']) ? $this->normalizeAndHash(strtolower(trim($data['user_data']['first_name']))) : null,
                //                 'ln' => !empty($data['user_data']['last_name']) ? $this->normalizeAndHash(strtolower(trim($data['user_data']['last_name']))) : null,
                //                 'external_id' => !empty($data['user_data']['customer_id']) ? $this->normalizeAndHash(strtolower(trim($data['user_data']['customer_id']))) : null,
                //                 'country' => $this->normalizeAndHash('bd'),
                //                 'st' => $this->normalizeAndHash($data['user_data']['state'] ?? null),
                //                 'ct' => $this->normalizeAndHash($data['user_data']['city'] ?? null),
                //                 'zp' => $this->normalizeAndHash($data['user_data']['zipcode'] ?? null),
                //             ],

                //         ],
                //     ],
                //     // 'test_event_code' => $this->test_event_code,
                // ];

                // $payload = json_decode(json_encode($payload, JSON_PRETTY_PRINT), true);
                // $client = new Client([
                //     'base_uri' => 'https://graph.facebook.com/v23.0/',
                // ]);
                // $response = $client->post($this->pixelId . '/events', [
                //     'query' => [
                //         'access_token' => $this->accessToken,
                //     ],
                //     'json' => $payload,
                // ]);



                // return response()->json([
                //     'status' => 'success',
                //     'data' => $data,
                //     'request' => $payload,
                //     'pixelData' => "Segment: " . $segment . " pixel id: " . $this->pixelId . " token:" . $this->accessToken,
                //     'response' => json_decode($response->getBody(), true)
                // ], 200);


                $pageViewEvent = new PageViewEvent();
                $pageViewEvent->push();
                $payload = $pageViewEvent->payload();
                SendMetaCapiEventJob::dispatch($payload)
                    ->onQueue(env('META_CAPI_QUEUE', 'metacapi'));
                // $response = $pageViewEvent->sendServerSide();
                return response()->json([
                    'status' => 'success',
                    'data' => $data,
                    'request' => $payload
                ], 200);
            }
            // Log::info('FB Pixel CAPI after pageView Started at ' . now());

            if ($request->event_name == 'view_content') {
                // Handle ViewContent event similarly
                $contents = [];
                if (isset($data['ecommerce']['items']) && count($data['ecommerce']['items']) > 0) {
                    foreach ($data['ecommerce']['items'] as $item) {
                        $contents[] = [
                            'id' => $item['item_id'] ? (int) $item['item_id'] : null,
                            'quantity' => $item['quantity'] ?? 1,
                            'item_price' => $item['price'] ?? null,
                        ];
                    }
                }

                $payload = [
                    'data' => [
                        [
                            'event_name' => 'ViewContent',
                            'action_source' => 'website',
                            'event_time' => time(),
                            'event_id' => $data['event_id'] ?? (string) Str::uuid(),
                            'event_source_url' => !empty($data['event_source_url']) ? $data['event_source_url'] : $request->url(),
                            'referrer_url' => !empty($data['referrer_url']) ? $data['referrer_url'] : $request->headers->get('referer'),

                            'custom_data' => [
                                'currency' => 'BDT',
                                'value' => $data['ecommerce']['value'] ?? null,
                                'contents' => $contents,
                                'category' => $segment,
                            ],
                            'user_data' => [
                                'client_user_agent' => $request->server('HTTP_USER_AGENT'),
                                'client_ip_address' => $request->ip(),
                                'fbp' => isset($_COOKIE['_fbp']) ? $_COOKIE['_fbp'] : null,
                                'fbc' => !empty($data['user_data']['fbc']) ? $data['user_data']['fbc'] : (isset($_COOKIE['custom_fbc']) ? $_COOKIE['custom_fbc'] : null),
                                'ph' => !empty($data['user_data']['phone_number']) ? $this->normalizeAndHash($data['user_data']['phone_number']) : null,
                                'fn' => !empty($data['user_data']['first_name']) ? $this->normalizeAndHash(strtolower(trim($data['user_data']['first_name']))) : null,
                                'ln' => !empty($data['user_data']['last_name']) ? $this->normalizeAndHash(strtolower(trim($data['user_data']['last_name']))) : null,
                                'external_id' => !empty($data['user_data']['customer_id']) ? $this->normalizeAndHash(strtolower(trim($data['user_data']['customer_id']))) : null,
                                'country' => $this->normalizeAndHash('bd'),
                                'st' => $this->normalizeAndHash($data['user_data']['state'] ?? null),
                                'ct' => $this->normalizeAndHash($data['user_data']['city'] ?? null),
                                'zp' => $this->normalizeAndHash($data['user_data']['zipcode'] ?? null),
                            ],
                        ],
                    ],
                    // 'test_event_code' => $this->test_event_code,
                ];

                $payload = json_decode(json_encode($payload, JSON_PRETTY_PRINT), true);
                $client = new Client([
                    'base_uri' => 'https://graph.facebook.com/v23.0/',
                ]);
                $response = $client->post($this->pixelId . '/events', [
                    'query' => [
                        'access_token' => $this->accessToken,
                    ],
                    'json' => $payload,
                ]);

                // return response()->json([
                //     'status' => 'success',
                //     'data' => $data,
                //     'request' => $payload,
                //     'pixelData' => $this->pixelId . " token:" . $this->accessToken,
                //     'response' => json_decode($response->getBody(), true)
                // ], 200);
            }
            //   Log::info('FB Pixel CAPI after viewContent Started at ' . now());
            if ($request->event_name == 'initiate_checkout') {
                // Handle ViewContent event similarly

                

                $contents = [];
                if (isset($data['ecommerce']['items']) && count($data['ecommerce']['items']) > 0) {
                    foreach ($data['ecommerce']['items'] as $item) {
                        $contents[] = [
                            'id' => $item['item_id'] ? (int) $item['item_id'] : null,
                            'quantity' => $item['quantity'] ?? 1,
                            'item_price' => $item['price'] ?? null,
                        ];
                    }
                }

                $payload = [
                    'data' => [
                        [
                            'event_name' => 'InitiateCheckout',
                            'action_source' => 'website',
                            'event_time' => time(),
                            'event_id' => $data['event_id'] ?? (string) Str::uuid(),
                            'event_source_url' => !empty($data['event_source_url']) ? $data['event_source_url'] : $request->url(),
                            'referrer_url' => !empty($data['referrer_url']) ? $data['referrer_url'] : $request->headers->get('referer'),

                            'custom_data' => [
                                'currency' => 'BDT',
                                'value' => $data['ecommerce']['value'] ?? null,
                                'contents' => $contents,
                                'category' => $segment,
                            ],
                            'user_data' => [
                                'client_user_agent' => $request->server('HTTP_USER_AGENT'),
                                'client_ip_address' => $request->ip(),
                                'fbp' => isset($_COOKIE['_fbp']) ? $_COOKIE['_fbp'] : null,
                                'fbc' => !empty($data['user_data']['fbc']) ? $data['user_data']['fbc'] : (isset($_COOKIE['custom_fbc']) ? $_COOKIE['custom_fbc'] : null),
                                'ph' => !empty($data['user_data']['phone_number']) ? $this->normalizeAndHash($data['user_data']['phone_number']) : null,
                                'fn' => !empty($data['user_data']['first_name']) ? $this->normalizeAndHash(strtolower(trim($data['user_data']['first_name']))) : null,
                                'ln' => !empty($data['user_data']['last_name']) ? $this->normalizeAndHash(strtolower(trim($data['user_data']['last_name']))) : null,
                                'external_id' => !empty($data['user_data']['customer_id']) ? $this->normalizeAndHash(strtolower(trim($data['user_data']['customer_id']))) : null,
                                'country' => $this->normalizeAndHash('bd'),
                                'st' => $this->normalizeAndHash($data['user_data']['state'] ?? null),
                                'ct' => $this->normalizeAndHash($data['user_data']['city'] ?? null),
                                'zp' => $this->normalizeAndHash($data['user_data']['zipcode'] ?? null),
                            ],
                        ],
                    ],

                    // 'test_event_code' => $this->test_event_code,
                ];

                $payload = json_decode(json_encode($payload, JSON_PRETTY_PRINT), true);
                $client = new Client([
                    'base_uri' => 'https://graph.facebook.com/v23.0/',
                ]);
                $response = $client->post($this->pixelId . '/events', [
                    'query' => [
                        'access_token' => $this->accessToken,
                    ],
                    'json' => $payload,
                ]);

                // return response()->json([
                //     'status' => 'success',
                //     'data' => $data,
                //     'request' => $payload,
                //     'response' => json_decode($response->getBody(), true)
                // ], 200);
            }
            Log::info('FB Pixel CAPI after initiatecheckout Started at ' . now());

            if ($request->event_name == 'purchase') {
                // Handle ViewContent event similarly


                $contents = [];
                if (isset($data['ecommerce']['items']) && count($data['ecommerce']['items']) > 0) {
                    foreach ($data['ecommerce']['items'] as $item) {
                        $contents[] = [
                            'id' => $item['item_id'] ? (int) $item['item_id'] : null,
                            'quantity' => $item['quantity'] ?? 1,
                            'item_price' => $item['price'] ?? null,
                        ];
                    }
                }

                $payload = [
                    'data' => [
                        [
                            'event_name' => 'Purchase',
                            'action_source' => 'website',
                            'event_time' => time(),
                            'event_id' => $data['event_id'] ?? (string) Str::uuid(),
                            'event_source_url' => !empty($data['event_source_url']) ? $data['event_source_url'] : $request->url(),
                            'referrer_url' => !empty($data['referrer_url']) ? $data['referrer_url'] : $request->headers->get('referer'),

                            'custom_data' => [
                                'currency' => 'BDT',
                                'value' => $data['ecommerce']['value'] ?? null,
                                'transaction_id' => $data['ecommerce']['transaction_id'] ?? null,
                                'contents' => $contents,
                                'category' => $segment,
                            ],
                            'user_data' => [
                                'client_user_agent' => $request->server('HTTP_USER_AGENT'),
                                'client_ip_address' => $request->ip(),
                                'fbp' => isset($_COOKIE['_fbp']) ? $_COOKIE['_fbp'] : null,
                                'fbc' => !empty($data['user_data']['fbc']) ? $data['user_data']['fbc'] : (isset($_COOKIE['custom_fbc']) ? $_COOKIE['custom_fbc'] : null),
                                'ph' => !empty($data['user_data']['phone_number']) ? $this->normalizeAndHash($data['user_data']['phone_number']) : null,
                                'fn' => !empty($data['user_data']['first_name']) ? $this->normalizeAndHash(strtolower(trim($data['user_data']['first_name']))) : null,
                                'ln' => !empty($data['user_data']['last_name']) ? $this->normalizeAndHash(strtolower(trim($data['user_data']['last_name']))) : null,
                                'external_id' => !empty($data['user_data']['customer_id']) ? $this->normalizeAndHash(strtolower(trim($data['user_data']['customer_id']))) : null,
                                'country' => $this->normalizeAndHash('bd'),
                                'st' => $this->normalizeAndHash($data['user_data']['state'] ?? null),
                                'ct' => $this->normalizeAndHash($data['user_data']['city'] ?? null),
                                'zp' => $this->normalizeAndHash($data['user_data']['zipcode'] ?? null),
                            ],
                        ],
                    ],
                    // 'test_event_code' => $this->test_event_code,
                ];

                $payload = json_decode(json_encode($payload, JSON_PRETTY_PRINT), true);
                $client = new Client([
                    'base_uri' => 'https://graph.facebook.com/v23.0/',
                ]);
                $response = $client->post($this->pixelId . '/events', [
                    'query' => [
                        'access_token' => $this->accessToken,
                    ],
                    'json' => $payload,
                ]);

                // return response()->json([
                //     'status' => 'success',
                //     'data' => $data,
                //     'request' => $payload,
                //     'response' => json_decode($response->getBody(), true)
                // ], 200);
                $order_id = $data['ecommerce']['transaction_id'];
                if (is_integer($order_id)) {
                    $order = Order::find($order_id);
                    if ($order) {
                        if ($order->trackingEvent) {

                            $order->trackingEvent()->update([
                                'is_fired' => true,
                                'json_data' => $response->getBody()->getContents(),
                                'event_fired_time' => now(),
                            ]);
                        }

                    }
                }


            }
            //   Log::info('FB Pixel CAPI after purchase Started at ' . now());

        } catch (\Exception $e) {
            Log::info('FB Pixel CAPI Calling Ends At: ' . now() . " error: " . $e->getMessage());
        }



        // Log::info('FB Pixel CAPI Calling Ends At: ' . now());

        // return response()->json(['message' => 'FB Pixel CAPI data received'], 200);
    }
    public function customFbPixelCAPI($id)
    {
        return response()->json([
            'status' => 'stopped',
        ]);
        $order = Order::findOrFail($id);
        $trackingEvent = $order->trackingEvent;
        $customer = $order->customer;
        if (!$trackingEvent) {
            return redirect()->back()->with('error', 'Tracking event not found');
        }

        $segment = $trackingEvent->segment;




        try {
            $filterData = ['men', 'women', 'kids'];

            if ($trackingEvent->segment) {
                $segment = strtolower($trackingEvent->segment);

                foreach ($filterData as $value) {
                    if (preg_match('/\b' . preg_quote($value, '/') . '\b/', $segment)) { // case-insensitive search, match full exact and full word
                        $segment = $value;
                        break; // stop at first match
                    }
                }
            }

            if ($trackingEvent->event_name == 'purchase') {
                // Handle ViewContent event similarly


                $contents = [];
                if (isset($order->Order_Item) && $order->Order_Item->count() > 0) {
                    foreach ($order->Order_Item as $item) {
                        $contents[] = [
                            'id' => $item->product_id ? (int) $item->product_id : null,
                            'quantity' => $item->quantity ?? 1,
                            'item_price' => $item->price ?? null,
                        ];
                    }
                }

                $payload = [
                    'data' => [
                        [
                            'event_name' => 'Purchase',
                            'action_source' => 'website',
                            'event_time' => time(),
                            'event_id' => $data['event_id'] ?? (string) Str::uuid(),
                            'event_source_url' => $trackingEvent->url ?? null,
                            'referrer_url' => $trackingEvent->referrer ?? null,

                            'custom_data' => [
                                'currency' => 'BDT',
                                'value' => $order->total ?? null,
                                'transaction_id' => $order->id ?? null,
                                'contents' => $contents,
                                'category' => $segment,
                            ],
                            'user_data' => [
                                'client_user_agent' => $trackingEvent->user_agent ?? null,
                                'client_ip_address' => $trackingEvent->ip_address ?? null,
                                'fbp' => isset($trackingEvent->tud_id) ? $this->normalizeAndHash($trackingEvent->tud_id) : null,
                                'fbc' => $trackingEvent->tracking_id ?? null,
                                'ph' => isset($order->phone) ? $this->normalizeAndHash($order->phone) : null,
                                'fn' => isset($order->name) ? $this->normalizeAndHash(strtolower(trim($order->name))) : null,
                                // 'ln' => !empty($data['user_data']['last_name']) ? $this->normalizeAndHash(strtolower(trim($data['user_data']['last_name']))) : null,
                                'external_id' => isset($customer?->id) ? $this->normalizeAndHash(strtolower(trim($customer->id))) : null,
                                'country' => $this->normalizeAndHash('bd'),
                                'st' => $customer?->state ? $this->normalizeAndHash($customer?->state) : null,
                                'ct' => $customer?->city ? $this->normalizeAndHash($customer?->city) : null,
                                'zp' => $customer?->zip_code ? $this->normalizeAndHash($customer?->zip_code) : null,
                            ],
                        ],
                    ],
                    // 'test_event_code' => $this->test_event_code,
                ];

                $payload = json_decode(json_encode($payload, JSON_PRETTY_PRINT), true);
                $client = new Client([
                    'base_uri' => 'https://graph.facebook.com/v23.0/',
                ]);
                $response = $client->post($this->pixelId . '/events', [
                    'query' => [
                        'access_token' => $this->accessToken,
                    ],
                    'json' => $payload,
                ]);

                return response()->json([
                    'status' => 'success',
                    'request' => $payload,
                    'response' => json_decode($response->getBody(), true)
                ], 200);
            }
            //   Log::info('FB Pixel CAPI after purchase Started at ' . now());

        } catch (\Exception $e) {
            Log::info('FB Pixel CAPI Calling Ends At: ' . now() . " error: " . $e->getMessage());
        }

    }

    public function normalizeAndHash($value)
    {
        if (empty($value))
            return null;               // skip null/empty
        $normalized = strtolower(trim($value));       // lowercase + trim
        return hash('sha256', $normalized);           // hash with SHA-256
    }


    //campaign method
    public function campaign($slug)
    {
        $campaign = Campaign::where('slug', $slug)->first();
        if (!$campaign) {
            abort(404);
        }
        $view_file = $campaign->view_file;


        $json = json_decode($campaign->json_data); // object
        // $json = json_decode(file_get_contents(base_path('resources/views/templates/landingpages/seldom_zaynah_eid.json'))); // object

        $data = $json->sections ?? [];
        // dd($data);

        $array = (array) $data->products;
        $delivery_areas = delivery_areas::all();
        $productids = array_map(function ($item) {
            return $item->id;
        }, $array ?? []);


        $products = products::whereIn('id', $productids)->get();
        $finalProducts = collect();

        // First, build a map of product info from JSON for easy lookup
        $productJsonMap = [];
        foreach ($data->products ?? [] as $item) {
            $productJsonMap[$item->id] = [
                'order' => $item->order ?? 0,
                'is_primary' => $item->is_primary ?? false,
            ];
        }

        // Loop through DB products and merge JSON info
        foreach ($products as $product) {
            if (isset($productJsonMap[$product->id])) {
                $product->order = $productJsonMap[$product->id]['order'];
                $product->is_primary = $productJsonMap[$product->id]['is_primary'];
                $finalProducts->push($product);
            }
        }

        // Sort by order
        $finalProducts = $finalProducts->sortBy('order')->values();

        // Check result
        // dd($finalProducts);
        $selected_products = $finalProducts->where('is_primary', true)->values();
        $related_products = products::where('status', 1)->where('stock_status', '!=', 'out_of_stock')->whereNotIn('id', $selected_products->pluck('id')->toArray())->inRandomOrder()->limit(8)->get();


        return view($view_file, compact('data', 'delivery_areas', 'finalProducts', 'selected_products', 'related_products'));
    }

    public function search(Request $request)
    {
        $query = $request->get('search');
        $products = products::where('status', 1)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('search', compact('products'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

}
