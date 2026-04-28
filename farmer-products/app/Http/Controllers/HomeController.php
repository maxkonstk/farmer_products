<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\FaqItem;
use App\Models\Farmer;
use App\Models\PromoBlock;
use App\Models\Product;
use App\Models\Testimonial;
use App\Services\StorefrontSettingsService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private readonly StorefrontSettingsService $storefrontSettings)
    {
    }

    public function index(): View
    {
        $featuredProducts = Product::query()
            ->with(['category', 'collections'])
            ->active()
            ->featured()
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $featuredCollections = Collection::query()
            ->withCount(['products' => fn ($query) => $query->active()->where('stock', '>', 0)])
            ->published()
            ->featured()
            ->activeWindow()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(3)
            ->get();

        $categories = Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        $farmers = Farmer::query()->published()->orderBy('sort_order')->orderBy('name')->get();
        $testimonials = Testimonial::query()->published()->orderBy('sort_order')->orderBy('author')->get();
        $faqItems = FaqItem::query()->published()->orderBy('sort_order')->orderBy('id')->get();
        $homePromos = PromoBlock::query()
            ->with(['category', 'collection', 'product'])
            ->published()
            ->activeWindow()
            ->forPlacement('home')
            ->orderBy('sort_order')
            ->latest()
            ->take(3)
            ->get();
        $delivery = $this->storefrontSettings->delivery();

        return view('home', [
            'featuredProducts' => $featuredProducts,
            'featuredCollections' => $featuredCollections,
            'categories' => $categories,
            'farmers' => $farmers->isNotEmpty() ? $farmers->toArray() : config('shop.farmers', []),
            'promises' => $this->storefrontSettings->promises(),
            'testimonials' => $testimonials->isNotEmpty() ? $testimonials->toArray() : config('shop.testimonials', []),
            'faqItems' => $faqItems->isNotEmpty() ? $faqItems->toArray() : config('shop.faq', []),
            'deliveryZones' => $delivery['zones'] ?? [],
            'homePromos' => $homePromos,
        ]);
    }
}
