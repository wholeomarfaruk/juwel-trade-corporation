<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Media;
use App\Services\Media\MediaUploadService;
use App\Services\Media\MediaVariantService;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

class BannerSeeder extends Seeder
{
    /**
     * Seed the banner zones with the same placeholder image the old
     * StorefrontData demo arrays used, so every zone has real Banner/Media
     * rows to manage from day one instead of sitting empty.
     */
    public function run(): void
    {
        $media = $this->seedDemoMedia();

        if (! $media) {
            $this->command?->warn('BannerSeeder: demo image not found, skipping.');
            return;
        }

        $rows = [
            // 2 side banners next to the hero slider
            ['zone' => Banner::ZONE_HERO_SIDE, 'sort_order' => 1],
            ['zone' => Banner::ZONE_HERO_SIDE, 'sort_order' => 2],

            // 1 full-width strip banner
            ['zone' => Banner::ZONE_PROMO_STRIP, 'sort_order' => 1],

            // 3 promo grid tiles
            ['zone' => Banner::ZONE_PROMO_GRID, 'sort_order' => 1],
            ['zone' => Banner::ZONE_PROMO_GRID, 'sort_order' => 2],
            ['zone' => Banner::ZONE_PROMO_GRID, 'sort_order' => 3],
        ];

        foreach ($rows as $row) {
            Banner::firstOrCreate(
                ['zone' => $row['zone'], 'sort_order' => $row['sort_order']],
                [
                    'title'    => null,
                    'link'     => null,
                    'image_id' => $media->id,
                    'status'   => 1,
                ]
            );
        }
    }

    protected function seedDemoMedia(): ?Media
    {
        $sourcePath = public_path('images/banner.avif');

        if (! file_exists($sourcePath)) {
            return null;
        }

        // Reuse a previously seeded copy if this seeder already ran.
        $existing = Media::where('original_name', 'banner.avif')
            ->where('category', 'banner-seed')
            ->first();

        if ($existing) {
            return $existing;
        }

        $file = new UploadedFile($sourcePath, 'banner.avif', 'image/avif', null, true);

        $media = app(MediaUploadService::class)->upload($file, [
            'category' => 'banner-seed',
        ]);

        app(MediaVariantService::class)->generate($media);

        return $media;
    }
}
