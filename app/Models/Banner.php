<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    // Zone keys — one per banner slot across the site. Add a new one here
    // when a new page/section needs its own managed banner zone.
    const ZONE_HERO_SIDE   = 'hero_side';
    const ZONE_PROMO_STRIP = 'promo_strip';
    const ZONE_PROMO_GRID  = 'promo_grid';

    public static function zones(): array
    {
        return [
            self::ZONE_HERO_SIDE   => 'Hero — side banners',
            self::ZONE_PROMO_STRIP => 'Homepage — promo strip',
            self::ZONE_PROMO_GRID  => 'Homepage — promo grid',
        ];
    }

    protected $fillable = [
        'title',
        'link',
        'zone',
        'image_id',
        'sort_order',
        'status',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function getImageUrl(): ?string
    {
        return $this->media?->getUrl();
    }

    public function scopeZone($query, string $zone)
    {
        return $query->where('zone', $zone);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
