<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        return $this->renderCatalog($request);
    }

    public function category(Request $request, Category $category): View
    {
        return $this->renderCatalog($request, $category);
    }

    public function collection(Request $request, Collection $collection): View
    {
        abort_unless(
            $collection->is_published
            && (! $collection->starts_at || $collection->starts_at->isPast())
            && (! $collection->ends_at || $collection->ends_at->isFuture()),
            404
        );

        return $this->renderCatalog($request, null, $collection);
    }

    private function renderCatalog(Request $request, ?Category $category = null, ?Collection $collection = null): View
    {
        $search = trim((string) $request->string('q'));
        $sort = (string) $request->string('sort', 'latest');
        $selectedCategorySlug = (string) $request->string('category');
        $selectedCollectionSlug = (string) $request->string('collection');
        $availability = (string) $request->string('availability');
        $seasonality = (string) $request->string('season');

        $selectedCategory = $category;
        $selectedCollection = $collection;

        if (! $selectedCategory && $selectedCategorySlug !== '') {
            $selectedCategory = Category::query()->firstWhere('slug', $selectedCategorySlug);
        }

        if (! $selectedCollection && $selectedCollectionSlug !== '') {
            $selectedCollection = Collection::query()
                ->published()
                ->activeWindow()
                ->firstWhere('slug', $selectedCollectionSlug);
        }

        $products = Product::query()
            ->with(['category', 'collections'])
            ->active()
            ->when($selectedCategory, fn (Builder $query) => $query->where('category_id', $selectedCategory->id))
            ->when(
                $selectedCollection,
                fn (Builder $query) => $query->whereHas(
                    'collections',
                    fn (Builder $innerQuery) => $innerQuery->where('collections.id', $selectedCollection->id)
                )
            )
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $innerQuery) use ($search): void {
                    $innerQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($availability === 'in-stock', fn (Builder $query) => $query->where('stock', '>', 0))
            ->when($seasonality !== '', fn (Builder $query) => $query->where('seasonality', 'like', "%{$seasonality}%"));

        match ($sort) {
            'price_asc' => $products->orderBy('price'),
            'price_desc' => $products->orderByDesc('price'),
            'name_asc' => $products->orderBy('name'),
            default => $products->latest(),
        };

        $products = $products->paginate(12)->withQueryString();

        return view('catalog.index', [
            'products' => $products,
            'currentCategory' => $selectedCategory,
            'currentCollection' => $selectedCollection,
            'search' => $search,
            'sort' => $sort,
            'availability' => $availability,
            'selectedSeasonality' => $seasonality,
            'categories' => Category::query()->orderBy('name')->get(),
            'collections' => Collection::query()
                ->published()
                ->activeWindow()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'seasonalityOptions' => Product::query()
                ->whereNotNull('seasonality')
                ->distinct()
                ->orderBy('seasonality')
                ->pluck('seasonality'),
        ]);
    }
}
