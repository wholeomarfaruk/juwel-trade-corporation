<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Juwel Trade Corporation — storefront homepage.
 *
 * This controller ships with hard-coded demo data so the page renders
 * immediately after install. Replace the arrays below (or the whole
 * `storeData()` method) with your Eloquent models / repositories once
 * the catalogue lives in the database.
 */
class StorefrontController extends Controller
{
    public function index(): View
    {
        return view('storefront.index', $this->storeData());
    }

    /** Tiny placeholder-image helper (swap for real product image URLs). */
    private function img(string $seed, int $size = 600): string
    {
        return "https://picsum.photos/seed/{$seed}/{$size}/{$size}";
    }

    private function storeData(): array
    {
        $categories = [
            ['name' => 'Orthopedic Supports', 'slug' => 'orthopedic',       'image' => $this->img('cat-orthopedic', 500), 'children' => ['Knee Support', 'Back Support', 'Ankle & Foot', 'Wrist & Hand', 'Neck Support', 'Shoulder Support']],
            ['name' => 'Medical Devices',     'slug' => 'medical-devices',   'image' => $this->img('cat-meddevices', 500), 'children' => ['Blood Pressure Monitor', 'Nebulizer', 'Thermometer', 'Oximeter', 'Stethoscope', 'Hearing Aid']],
            ['name' => 'Physiotherapy',       'slug' => 'physiotherapy',     'image' => $this->img('cat-physio', 500),     'children' => ['TENS Machine', 'Infrared Items', 'Muscle Stimulator']],
            ['name' => 'Body Massager',       'slug' => 'body-massager',     'image' => $this->img('cat-massager', 500),   'children' => ['Massage Gun', 'Head Massager', 'Foot Spa']],
            ['name' => 'Health Care',         'slug' => 'health-care',       'image' => $this->img('cat-healthcare', 500), 'children' => ['Pain Relief Cream', 'Medicine Box', 'Diabetes Care']],
            ['name' => 'Anatomical Models',   'slug' => 'anatomical-models', 'image' => $this->img('cat-anatomy', 500),    'children' => ['Skeleton Models', 'Medical Charts']],
            ['name' => 'Lifestyle',           'slug' => 'lifestyle',         'image' => $this->img('cat-lifestyle', 500),  'children' => ['Weight Scale', 'Beauty Care', 'Personal Care', 'Baby & Mom Care']],
            ['name' => 'Gym & Sports',        'slug' => 'gym-sports',        'image' => $this->img('cat-gymsports', 500),  'children' => ['Belt & Brace', 'Kinesiology Tape', 'Resistance Bands']],
            ['name' => 'Medical Disposal',    'slug' => 'medical-disposal',  'image' => $this->img('cat-disposal', 500),   'children' => ['Gloves', 'Masks', 'Bandage', 'Alcohol Pad']],
        ];

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
}
