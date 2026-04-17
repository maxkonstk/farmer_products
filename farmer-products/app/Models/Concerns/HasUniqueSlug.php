<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUniqueSlug
{
    protected static function bootHasUniqueSlug(): void
    {
        static::saving(function (Model $model): void {
            $slugColumn = $model->getSlugColumn();
            $sourceColumn = $model->getSlugSourceColumn();

            if (
                blank($model->{$slugColumn})
                || ($model->isDirty($sourceColumn) && ! $model->isDirty($slugColumn))
            ) {
                $model->{$slugColumn} = $model->generateUniqueSlug(
                    Str::slug((string) $model->{$sourceColumn})
                );
            }
        });
    }

    protected function getSlugColumn(): string
    {
        return 'slug';
    }

    protected function getSlugSourceColumn(): string
    {
        return 'name';
    }

    protected function generateUniqueSlug(string $baseSlug): string
    {
        $slugColumn = $this->getSlugColumn();
        $baseSlug = $baseSlug !== '' ? $baseSlug : Str::lower(Str::random(8));
        $slug = $baseSlug;
        $counter = 2;

        while (static::query()
            ->where($slugColumn, $slug)
            ->when($this->exists, fn ($query) => $query->whereKeyNot($this->getKey()))
            ->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
