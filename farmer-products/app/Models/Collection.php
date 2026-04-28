<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Collection extends Model
{
    use HasFactory;
    use HasUniqueSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'intro',
        'badge',
        'image',
        'is_published',
        'is_featured',
        'sort_order',
        'starts_at',
        'ends_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withTimestamps();
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    public function scopeActiveWindow(Builder $query): void
    {
        $query
            ->where(function (Builder $innerQuery): void {
                $innerQuery->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $innerQuery): void {
                $innerQuery->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getImageUrlAttribute(): string
    {
        if (blank($this->image)) {
            return '/images/products/hero-farm.svg';
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        if (Str::startsWith($this->image, '/')) {
            return file_exists(public_path(ltrim($this->image, '/')))
                ? $this->image
                : '/images/products/hero-farm.svg';
        }

        return Storage::disk('public')->url($this->image);
    }

    public function hasManagedImage(): bool
    {
        return filled($this->image) && ! Str::startsWith($this->image, ['http://', 'https://', '/']);
    }
}
