<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        abort_unless($product->is_active, Response::HTTP_NOT_FOUND);

        $product->load(['category', 'collections' => fn ($query) => $query->published()->activeWindow()->orderBy('sort_order')]);

        $relatedProducts = Product::query()
            ->with(['category', 'collections'])
            ->active()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest()
            ->take(4)
            ->get();

        $recommendedProducts = Product::query()
            ->with(['category', 'collections'])
            ->active()
            ->where('stock', '>', 0)
            ->whereKeyNot($product->id)
            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'recommendedProducts' => $recommendedProducts,
        ]);
    }
}
