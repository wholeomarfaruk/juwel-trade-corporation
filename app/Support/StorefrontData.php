<?php

namespace App\Support;

/**
 * Single source of truth for the JTC storefront's shared shell data
 * (categories used by the header search dropdown, off-canvas menu and footer).
 */
class StorefrontData
{
    /** Placeholder-image helper (swap for real category image URLs in Phase 2). */
    public static function img(string $seed, int $size = 500): string
    {
        return "https://picsum.photos/seed/{$seed}/{$size}/{$size}";
    }

    /** Real categories shared across the whole storefront shell (header search, off-canvas menu, footer). */
    public static function categories(): array
    {
        return \App\Models\Category::where('is_active', 1)
            ->where('is_show_in_menu', 1)
            ->whereNull('parent_id')
            ->orderBy('display_order')
            ->get()
            ->map(fn ($cat) => [
                'name'  => $cat->name,
                'slug'  => $cat->slug,
                'image' => $cat->getImageUrl() ?? asset('images/category.avif'),
            ])
            ->all();
    }

    /** Demo product catalogue shared across storefront homepage sections. */
    public static function products(): array
    {
        return [
            ['id' => 1,  'name' => 'Tynor Knee Support Sportif Neoprene J09', 'sku' => 'J09',      'image' => asset('images/product.webp'),      'price' => 1195, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Tynor',      'category' => 'orthopedic',       'badge' => null,   'tags' => ['best', 'browse']],
            ['id' => 2,  'name' => 'ALPK2 Aneroid Sphygmomanometer',         'sku' => '32507329', 'image' => asset('images/product.webp'),        'price' => 1499, 'compare_at_price' => 1860, 'price_max' => null, 'brand' => 'ALPK2',      'category' => 'medical-devices',  'badge' => 'deal', 'tags' => ['deal', 'browse']],
            ['id' => 3,  'name' => 'Adjustable Bed Backrest Support',         'sku' => '57600967', 'image' => asset('images/product.webp'),  'price' => 1895, 'compare_at_price' => 3025, 'price_max' => null, 'brand' => 'Samson',     'category' => 'orthopedic',       'badge' => 'deal', 'tags' => ['deal', 'best']],
            ['id' => 4,  'name' => 'Fascia Gun Light Age 9-Head Massager',    'sku' => '16053546', 'image' => asset('images/product.webp'),'price' => 960,  'compare_at_price' => 1850, 'price_max' => null, 'brand' => 'Axon',       'category' => 'body-massager',    'badge' => 'deal', 'tags' => ['deal', 'best', 'browse']],
            ['id' => 5,  'name' => 'Human Knee Joint Anatomical Model',       'sku' => '38433051', 'image' => asset('images/product.webp'),     'price' => 3100, 'compare_at_price' => 4695, 'price_max' => null, 'brand' => 'Samson',     'category' => 'anatomical-models','badge' => 'deal', 'tags' => ['deal']],
            ['id' => 6,  'name' => 'Digital Scale Bravo XT SS-05',            'sku' => '01544711', 'image' => asset('images/product.webp'),     'price' => 1799, 'compare_at_price' => 2120, 'price_max' => null, 'brand' => 'Camry',      'category' => 'lifestyle',        'badge' => 'deal', 'tags' => ['deal', 'scale']],
            ['id' => 7,  'name' => 'Infrared Rolling Massager',               'sku' => '70647533', 'image' => asset('images/product.webp'),  'price' => 2000, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Axon',       'category' => 'physiotherapy',    'badge' => null,   'tags' => ['massager', 'browse']],
            ['id' => 8,  'name' => 'Jumper Digital Blood Pressure Machine',   'sku' => '03369693', 'image' => asset('images/product.webp'),       'price' => 2350, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Jumper',     'category' => 'medical-devices',  'badge' => 'new',  'tags' => ['new']],
            ['id' => 9,  'name' => 'Procare Classic Steel Stethoscope',       'sku' => '03367755', 'image' => asset('images/product.webp'),     'price' => 1200, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Samson',     'category' => 'medical-devices',  'badge' => 'new',  'tags' => ['new', 'browse']],
            ['id' => 10, 'name' => 'Tiger Balm White Ointment 10g',           'sku' => '40617006', 'image' => asset('images/product.webp'),      'price' => 300,  'compare_at_price' => null, 'price_max' => null, 'brand' => 'Tiger Balm', 'category' => 'health-care',      'badge' => 'new',  'tags' => ['new', 'medicine']],
            ['id' => 11, 'name' => 'Donut Pillow Seat Cushion',               'sku' => '03365844', 'image' => asset('images/product.webp'),    'price' => 420,  'compare_at_price' => null, 'price_max' => null, 'brand' => 'Tynor',      'category' => 'orthopedic',       'badge' => 'new',  'tags' => ['new']],
            ['id' => 12, 'name' => 'Portable Fingertip Pulse Oximeter LK87',  'sku' => '03352008', 'image' => asset('images/product.webp'),       'price' => 400,  'compare_at_price' => null, 'price_max' => null, 'brand' => 'Beurer',     'category' => 'medical-devices',  'badge' => 'new',  'tags' => ['new']],
            ['id' => 13, 'name' => 'Tynor Wrist Brace With Thumb E06',        'sku' => '58705418', 'image' => asset('images/product.webp'),     'price' => 400,  'compare_at_price' => null, 'price_max' => null, 'brand' => 'Tynor',      'category' => 'orthopedic',       'badge' => null,   'tags' => ['best', 'browse']],
            ['id' => 14, 'name' => 'Therapy TENS Machine Gel Pad',            'sku' => 'KF5050',   'image' => asset('images/product.webp'),    'price' => 135,  'compare_at_price' => null, 'price_max' => 215,  'brand' => 'Axon',       'category' => 'physiotherapy',    'badge' => null,   'tags' => ['best', 'browse']],
            ['id' => 15, 'name' => 'Heel Guard Pain Protector (Soft Gel)',    'sku' => '38553586', 'image' => asset('images/product.webp'),      'price' => 180,  'compare_at_price' => 350,  'price_max' => null, 'brand' => 'Tynor',      'category' => 'orthopedic',       'badge' => 'deal', 'tags' => ['deal', 'best']],
            ['id' => 16, 'name' => 'Tynor Posture Corrector A33',             'sku' => 'A33',      'image' => asset('images/product.webp'),   'price' => 1195, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Tynor',      'category' => 'orthopedic',       'badge' => null,   'tags' => ['browse', 'best']],
            ['id' => 17, 'name' => 'Premium Slimming Belt Massager',          'sku' => '01610657', 'image' => asset('images/product.webp'),  'price' => 2400, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Axon',       'category' => 'body-massager',    'badge' => null,   'tags' => ['massager']],
            ['id' => 18, 'name' => 'Premium Hand Massager',                   'sku' => '01607123', 'image' => asset('images/product.webp'),  'price' => 4000, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Axon',       'category' => 'body-massager',    'badge' => null,   'tags' => ['massager']],
            ['id' => 19, 'name' => 'Human Anatomy Chart',                     'sku' => 'HAC',      'image' => asset('images/product.webp'),     'price' => 499,  'compare_at_price' => 760,  'price_max' => null, 'brand' => 'Samson',     'category' => 'anatomical-models','badge' => 'deal', 'tags' => ['deal', 'model']],
            ['id' => 20, 'name' => 'Mini Human Skeleton Anatomy Model',       'sku' => '16559457', 'image' => asset('images/product.webp'),  'price' => 1850, 'compare_at_price' => 2255, 'price_max' => null, 'brand' => 'Samson',     'category' => 'anatomical-models','badge' => 'deal', 'tags' => ['deal', 'model']],
            ['id' => 21, 'name' => 'Camry Weight Scale 7009',                 'sku' => '01410632', 'image' => asset('images/product.webp'),    'price' => 1300, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Camry',      'category' => 'lifestyle',        'badge' => null,   'tags' => ['scale', 'browse']],
            ['id' => 22, 'name' => 'Beurer Scale PS 240',                     'sku' => '01540550', 'image' => asset('images/product.webp'),    'price' => 2900, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Beurer',     'category' => 'lifestyle',        'badge' => null,   'tags' => ['scale']],
            ['id' => 23, 'name' => 'Kinesiology Elastic Muscle Tape',         'sku' => '01556198', 'image' => asset('images/product.webp'),      'price' => 470,  'compare_at_price' => null, 'price_max' => null, 'brand' => 'Tynor',      'category' => 'gym-sports',       'badge' => null,   'tags' => ['browse', 'best']],
            ['id' => 24, 'name' => 'Nebulizer Machine Compact',               'sku' => '03361999', 'image' => asset('images/product.webp'),       'price' => 1650, 'compare_at_price' => null, 'price_max' => null, 'brand' => 'Dr. Trust',  'category' => 'medical-devices',  'badge' => null,   'tags' => ['browse']],
        ];
    }

    /** Decorate a raw demo product with the display fields product-card.blade.php needs. */
    public static function decorateProduct(array $p): array
    {
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
    }

    /** Decorated demo products carrying the given tag (e.g. 'deal', 'best', 'browse'). */
    public static function productsByTag(string $tag): array
    {
        return array_values(array_map(
            fn (array $p) => self::decorateProduct($p),
            array_filter(self::products(), fn (array $p) => in_array($tag, $p['tags'], true))
        ));
    }

    /** Demo promo banner images (used for hero side banners + promo grid). */
    public static function promos(): array
    {
        return [
            ['image' => asset('images/banner.avif')],
            ['image' => asset('images/banner.avif')],
            ['image' => asset('images/banner.avif')],
        ];
    }

    /** Demo hero slides. */
    public static function slides(): array
    {
        return [
            ['image' => asset('images/banner.avif')],
            ['image' => asset('images/banner.avif')],
            ['image' => asset('images/banner.avif')],
        ];
    }

    /** Decorate a real \App\Models\products record with the display fields product-card.blade.php needs. */
    public static function decorateEloquentProduct($product): array
    {
        $price        = (float) ($product->price ?? 0);
        $comparePrice = (float) ($product->discount_price ?? 0);
        $isCompare    = $comparePrice > 0 && $comparePrice < $price;
        $pct          = $isCompare ? (int) round((1 - $comparePrice / $price) * 100) : 0;
        $money        = fn ($n) => '৳' . number_format((int) $n);

        return [
            'id'             => $product->id,
            'name'           => $product->name ?? '',
            'url'            => rescue(fn () => $product->url, '#', report: false),
            'image'          => $product->getImageFullUrl() ?? asset('images/no-thumbnail.png'),
            'showNew'        => false,
            'showDealPct'    => $isCompare,
            'pctText'        => '-' . $pct . '%',
            'priceIsRange'   => false,
            'priceIsCompare' => $isCompare,
            'priceSingle'    => ! $isCompare,
            'priceText'      => $isCompare ? $money($comparePrice) : $money($price),
            'compareText'    => $isCompare ? $money($price) : '',
            'rangeText'      => '',
        ];
    }

    /**
     * Live-search results shared by the desktop header search and the
     * tablet/mobile search modal — same rules, same result shape, one place
     * to tune (result cap, ordering, which fields are matched).
     */
    public static function searchProducts(string $query, ?string $category = null, int $limit = 8)
    {
        $q = trim($query);

        if ($q === '') {
            return collect();
        }

        return \App\Models\products::where('status', 1)
            ->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            })
            ->when($category, function ($builder) use ($category) {
                $builder->whereHas('categories', function ($cat) use ($category) {
                    $cat->where('name', $category);
                });
            })
            ->orderByDesc('created_at')
            ->take($limit)
            ->get()
            ->map(fn ($product) => self::decorateEloquentProduct($product));
    }

    /** Category name chips for the "Discover more" section (categories flagged homepage_category = true). */
    public static function discoverChips(int $limit = 18): array
    {
        return \App\Models\Category::where('is_active', 1)
            ->where('homepage_category', 1)
            ->orderBy('display_order')
            ->limit($limit)
            ->pluck('name')
            ->all();
    }
}
