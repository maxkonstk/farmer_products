<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountFavoriteController extends Controller
{
    public function index(): View
    {
        $products = auth()->user()
            ->favoriteProducts()
            ->with('category')
            ->active()
            ->orderByDesc('favorites.created_at')
            ->paginate(12);

        return view('account.favorites.index', compact('products'));
    }

    public function store(Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        auth()->user()->favoriteProducts()->syncWithoutDetaching([$product->id]);

        return back()->with('success', "Товар «{$product->name}» сохранен в избранном.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        auth()->user()->favoriteProducts()->detach($product->id);

        return back()->with('success', "Товар «{$product->name}» убран из избранного.");
    }
}
