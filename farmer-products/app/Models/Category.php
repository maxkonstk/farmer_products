<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;
    use HasUniqueSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getImageUrlAttribute(): string
    {
        if (blank($this->image)) {
            return '/images/categories/vegetables.jpg';
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        if (Str::startsWith($this->image, '/')) {
            return file_exists(public_path(ltrim($this->image, '/')))
                ? $this->image
                : '/images/categories/vegetables.jpg';
        }

        return Storage::disk('public')->url($this->image);
    }

    public function hasManagedImage(): bool
    {
        return filled($this->image) && ! Str::startsWith($this->image, ['http://', 'https://', '/']);
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('navigation.categories'));
        static::deleted(fn () => Cache::forget('navigation.categories'));
    }
}
