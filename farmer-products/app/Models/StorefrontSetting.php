<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class StorefrontSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_name',
        'brand_tagline',
        'brand_city',
        'brand_address',
        'brand_phone',
        'brand_email',
        'hero_note',
        'brand_hours',
        'delivery_cutoff',
        'pickup_address',
        'delivery_windows',
        'delivery_zones',
        'delivery_promises',
        'storefront_promises',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'brand_hours' => 'array',
            'delivery_windows' => 'array',
            'delivery_zones' => 'array',
            'delivery_promises' => 'array',
            'storefront_promises' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('storefront.settings.snapshot'));
        static::deleted(fn () => Cache::forget('storefront.settings.snapshot'));
    }
}
