<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::query()
            ->with('category')
            ->active()
            ->featured()
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
