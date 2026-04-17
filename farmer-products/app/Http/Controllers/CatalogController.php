<?php

namespace App\Http\Controllers;

use App\Models\Category;
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

    private function renderCatalog(Request $request, ?Category $category = null): View
    {
        $search = trim((string) $request->string('q'));
        $sort = (string) $request->string('sort', 'latest');

        $products = Product::query()
            ->with('category')
            ->active()
            ->when($category, fn (Builder $query) => $query->where('category_id', $category->id))
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $innerQuery) use ($search): void {
                    $innerQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            });

        match ($sort) {
            'price_asc' => $products->orderBy('price'),
            'price_desc' => $products->orderByDesc('price'),
            'name_asc' => $products->orderBy('name'),
            default => $products->latest(),
        };

        $products = $products->paginate(12)->withQueryString();

        return view('catalog.index', [
            'products' => $products,
            'currentCategory' => $category,
            'search' => $search,
            'sort' => $sort,
        ]);
    }
}
