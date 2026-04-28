<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PromoBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'eyebrow',
        'badge',
        'title',
        'body',
        'cta_label',
        'cta_url',
        'image',
        'theme',
        'placement',
        'sort_order',
        'starts_at',
        'ends_at',
        'is_published',
        'category_id',
        'collection_id',
        'product_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function placements(): array
    {
        return [
            'home' => 'Главная витрина',
            'catalog' => 'Каталог и подборки',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function themes(): array
    {
        return [
            'wheat' => 'Теплый пшеничный',
            'sage' => 'Шалфейный',
            'charcoal' => 'Графитовый',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
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

    public function scopeForPlacement(Builder $query, string $placement): void
    {
        $query->where('placement', $placement);
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

    public function getResolvedUrlAttribute(): string
    {
        if (filled($this->cta_url)) {
            return $this->cta_url;
        }

        if ($this->product) {
            return route('products.show', $this->product);
        }

        if ($this->collection) {
            return route('collections.show', $this->collection);
        }

        if ($this->category) {
            return route('categories.show', $this->category);
        }

        return route('catalog.index');
    }

    public function getResolvedCtaLabelAttribute(): string
    {
        if (filled($this->cta_label)) {
            return $this->cta_label;
        }

        if ($this->product) {
            return 'Открыть товар';
        }

        if ($this->collection) {
            return 'Открыть подборку';
        }

        if ($this->category) {
            return 'Смотреть категорию';
        }

        return 'Перейти в каталог';
    }

    public function getPlacementLabelAttribute(): string
    {
        return self::placements()[$this->placement] ?? $this->placement;
    }

    public function getThemeLabelAttribute(): string
    {
        return self::themes()[$this->theme] ?? $this->theme;
    }

    public function getTargetLabelAttribute(): string
    {
        return $this->product?->name
            ?? $this->collection?->name
            ?? $this->category?->name
            ?? 'Общий сценарий';
    }
}
