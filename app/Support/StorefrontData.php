<?php

namespace App\Support;

/**
 * Single source of truth for the JTC storefront's shared shell data
 * (categories used by the header search dropdown, off-canvas menu and footer).
 *
 * Phase 1 ships demo categories so every page has a working nav without a DB.
 * Phase 2: replace categories() with real Category model data.
 */
class StorefrontData
{
    /** Placeholder-image helper (swap for real category image URLs in Phase 2). */
    public static function img(string $seed, int $size = 500): string
    {
        return "https://picsum.photos/seed/{$seed}/{$size}/{$size}";
    }

    /** Demo categories shared across the whole storefront shell. */
    public static function categories(): array
    {
        return [
            ['name' => 'Orthopedic Supports', 'slug' => 'orthopedic',       'image' => self::img('cat-orthopedic'), 'children' => ['Knee Support', 'Back Support', 'Ankle & Foot', 'Wrist & Hand', 'Neck Support', 'Shoulder Support']],
            ['name' => 'Medical Devices',     'slug' => 'medical-devices',   'image' => self::img('cat-meddevices'), 'children' => ['Blood Pressure Monitor', 'Nebulizer', 'Thermometer', 'Oximeter', 'Stethoscope', 'Hearing Aid']],
            ['name' => 'Physiotherapy',       'slug' => 'physiotherapy',     'image' => self::img('cat-physio'),     'children' => ['TENS Machine', 'Infrared Items', 'Muscle Stimulator']],
            ['name' => 'Body Massager',       'slug' => 'body-massager',     'image' => self::img('cat-massager'),   'children' => ['Massage Gun', 'Head Massager', 'Foot Spa']],
            ['name' => 'Health Care',         'slug' => 'health-care',       'image' => self::img('cat-healthcare'), 'children' => ['Pain Relief Cream', 'Medicine Box', 'Diabetes Care']],
            ['name' => 'Anatomical Models',   'slug' => 'anatomical-models', 'image' => self::img('cat-anatomy'),    'children' => ['Skeleton Models', 'Medical Charts']],
            ['name' => 'Lifestyle',           'slug' => 'lifestyle',         'image' => self::img('cat-lifestyle'),  'children' => ['Weight Scale', 'Beauty Care', 'Personal Care', 'Baby & Mom Care']],
            ['name' => 'Gym & Sports',        'slug' => 'gym-sports',        'image' => self::img('cat-gymsports'),  'children' => ['Belt & Brace', 'Kinesiology Tape', 'Resistance Bands']],
            ['name' => 'Medical Disposal',    'slug' => 'medical-disposal',  'image' => self::img('cat-disposal'),   'children' => ['Gloves', 'Masks', 'Bandage', 'Alcohol Pad']],
        ];
    }
}
