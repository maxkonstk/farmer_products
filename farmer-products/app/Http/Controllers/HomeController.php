<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FaqItem;
use App\Models\Farmer;
use App\Models\Product;
use App\Models\Testimonial;
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

        $farmers = Farmer::query()->published()->orderBy('sort_order')->orderBy('name')->get();
        $testimonials = Testimonial::query()->published()->orderBy('sort_order')->orderBy('author')->get();
        $faqItems = FaqItem::query()->published()->orderBy('sort_order')->orderBy('id')->get();

        return view('home', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'farmers' => $farmers->isNotEmpty() ? $farmers->toArray() : config('shop.farmers', []),
            'promises' => config('shop.promises', []),
            'testimonials' => $testimonials->isNotEmpty() ? $testimonials->toArray() : config('shop.testimonials', []),
            'faqItems' => $faqItems->isNotEmpty() ? $faqItems->toArray() : config('shop.faq', []),
            'deliveryZones' => config('shop.delivery.zones', []),
        ]);
    }
}
