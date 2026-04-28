<?php

namespace App\Providers;

use App\Models\Category;
use App\Services\CartService;
use App\Services\StorefrontSettingsService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('cart', function (Request $request): Limit {
            return Limit::perMinute($request->user() ? 80 : 40)
                ->by((string) ($request->user()?->id ?? $request->ip()));
        });

        RateLimiter::for('checkout', function (Request $request): Limit {
            return Limit::perMinute($request->user() ? 10 : 5)
                ->by((string) ($request->user()?->id ?? $request->ip()));
        });

        View::composer('*', function ($view): void {
            $navigationCategories = collect();
            $cartItemsCount = 0;
            $favoriteProductIds = [];
            $shopBrand = config('shop.brand', []);
            $shopDelivery = config('shop.delivery', []);
            $shopPromises = config('shop.promises', []);

            try {
                if (Schema::hasTable('categories')) {
                    $navigationCategories = collect(Cache::remember(
                        'navigation.categories',
                        now()->addSeconds(config('shop.navigation_cache_ttl', 3600)),
                        fn () => Category::query()
                            ->orderBy('name')
                            ->get(['name', 'slug'])
                            ->map(fn (Category $category) => [
                                'name' => $category->name,
                                'slug' => $category->slug,
                            ])
                            ->all()
                    ));
                }

                if (! app()->runningInConsole()) {
                    $cartItemsCount = app(CartService::class)->count();
                }

                if (auth()->check() && Schema::hasTable('favorites')) {
                    $favoriteProductIds = auth()->user()
                        ->favoriteProducts()
                        ->pluck('products.id')
                        ->map(fn ($id): int => (int) $id)
                        ->all();
                }

                $storefrontSettings = app(StorefrontSettingsService::class);
                $shopBrand = $storefrontSettings->brand();
                $shopDelivery = $storefrontSettings->delivery();
                $shopPromises = $storefrontSettings->promises();
            } catch (Throwable) {
                $navigationCategories = collect();
                $cartItemsCount = 0;
                $favoriteProductIds = [];
                $shopBrand = config('shop.brand', []);
                $shopDelivery = config('shop.delivery', []);
                $shopPromises = config('shop.promises', []);
            }

            $view->with('navigationCategories', $navigationCategories);
            $view->with('cartItemsCount', $cartItemsCount);
            $view->with('favoriteProductIds', $favoriteProductIds);
            $view->with('shopBrand', $shopBrand);
            $view->with('shopDelivery', $shopDelivery);
            $view->with('shopPromises', $shopPromises);
        });
    }
}
