<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\FaqItem;
use App\Models\Farmer;
use App\Models\Product;
use App\Services\StorefrontSettingsService;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(private readonly StorefrontSettingsService $storefrontSettings)
    {
    }

    public function about(): View
    {
        $farmers = Farmer::query()->published()->orderBy('sort_order')->orderBy('name')->get();

        return view('pages.about', [
            'farmers' => $farmers->isNotEmpty() ? $farmers->toArray() : config('shop.farmers', []),
            'promises' => $this->storefrontSettings->promises(),
        ]);
    }

    public function contacts(): View
    {
        $delivery = $this->storefrontSettings->delivery();

        return view('pages.contacts', [
            'deliveryZones' => $delivery['zones'] ?? [],
            'deliveryPromises' => $delivery['promises'] ?? [],
        ]);
    }

    public function delivery(): View
    {
        $delivery = $this->storefrontSettings->delivery();

        return view('pages.delivery', [
            'deliveryWindows' => $delivery['windows'] ?? [],
            'deliveryZones' => $delivery['zones'] ?? [],
            'deliveryPromises' => $delivery['promises'] ?? [],
        ]);
    }

    public function payment(): View
    {
        return view('pages.payment');
    }

    public function faq(): View
    {
        $faqItems = FaqItem::query()->published()->orderBy('sort_order')->orderBy('id')->get();

        return view('pages.faq', [
            'faqItems' => $faqItems->isNotEmpty() ? $faqItems->toArray() : config('shop.faq', []),
        ]);
    }

    public function sitemap(): Response
    {
        $urls = [
            ['loc' => route('home'), 'lastmod' => now()],
            ['loc' => route('catalog.index'), 'lastmod' => now()],
            ['loc' => route('pages.about'), 'lastmod' => now()],
            ['loc' => route('pages.contacts'), 'lastmod' => now()],
            ['loc' => route('pages.delivery'), 'lastmod' => now()],
            ['loc' => route('pages.payment'), 'lastmod' => now()],
            ['loc' => route('pages.faq'), 'lastmod' => now()],
        ];

        $categories = Category::query()->orderBy('updated_at', 'desc')->get()
            ->map(fn (Category $category) => [
                'loc' => route('categories.show', $category),
                'lastmod' => $category->updated_at,
            ]);

        $collections = Collection::query()
            ->published()
            ->activeWindow()
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(fn (Collection $collection) => [
                'loc' => route('collections.show', $collection),
                'lastmod' => $collection->updated_at,
            ]);

        $products = Product::query()->active()->orderBy('updated_at', 'desc')->get()
            ->map(fn (Product $product) => [
                'loc' => route('products.show', $product),
                'lastmod' => $product->updated_at,
            ]);

        return response()
            ->view('sitemap', [
                'urls' => collect($urls)->concat($categories)->concat($collections)->concat($products),
            ])
            ->header('Content-Type', 'application/xml');
    }
}
