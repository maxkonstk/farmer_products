<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    use HasUniqueSlug;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'producer_name',
        'origin_location',
        'seasonality',
        'taste_notes',
        'storage_info',
        'shelf_life',
        'delivery_note',
        'badge',
        'ingredients',
        'highlights',
        'gallery',
        'price',
        'image',
        'weight',
        'stock',
        'is_active',
        'is_featured',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'highlights' => 'array',
            'gallery' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class)
            ->withTimestamps();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->resolveImagePath($this->image);
    }

    public function hasManagedImage(): bool
    {
        return filled($this->image) && ! Str::startsWith($this->image, ['http://', 'https://', '/']);
    }

    /**
     * @return array<int, string>
     */
    public function getGalleryUrlsAttribute(): array
    {
        $gallery = collect($this->gallery ?? [])
            ->map(fn ($path) => $this->resolveImagePath((string) $path))
            ->filter()
            ->prepend($this->image_url)
            ->unique()
            ->values()
            ->all();

        return $gallery === [] ? ['/images/products/fallback.svg'] : $gallery;
    }

    /**
     * @return array<int, string>
     */
    public function getHighlightsListAttribute(): array
    {
        $highlights = collect($this->highlights ?? [])->filter()->values()->all();

        if ($highlights !== []) {
            return $highlights;
        }

        return array_values(array_filter([
            $this->producer_name ? "Поставщик: {$this->producer_name}" : null,
            $this->origin_location ? "Регион: {$this->origin_location}" : null,
            $this->seasonality ? "Сезон: {$this->seasonality}" : null,
            $this->shelf_life ? "Срок хранения: {$this->shelf_life}" : null,
        ]));
    }

    private function resolveImagePath(?string $image): string
    {
        if (blank($image)) {
            return '/images/products/fallback.svg';
        }

        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }

        if (Str::startsWith($image, '/')) {
            return file_exists(public_path(ltrim($image, '/')))
                ? $image
                : '/images/products/fallback.svg';
        }

        return Storage::disk('public')->url($image);
    }
}
