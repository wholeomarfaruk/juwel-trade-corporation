<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image_id',
        'status',
        'sort_order',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function getImageUrl(): ?string
    {
        return $this->media?->getUrl();
    }

    public function products()
    {
        return $this->hasMany(products::class, 'brand_id');
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
