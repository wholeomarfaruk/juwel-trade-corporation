<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
        'title',
        'link',
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
}
