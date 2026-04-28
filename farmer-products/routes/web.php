<?php

use App\Http\Controllers\AccountOrderController;
use App\Http\Controllers\AccountAddressController;
use App\Http\Controllers\AccountFavoriteController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CollectionController as AdminCollectionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PromoBlockController as AdminPromoBlockController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\StorefrontSettingsController as AdminStorefrontSettingsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/categories/{category:slug}', [CatalogController::class, 'category'])->name('categories.show');
Route::get('/collections/{collection:slug}', [CatalogController::class, 'collection'])->name('collections.show');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/contacts', [PageController::class, 'contacts'])->name('pages.contacts');
Route::get('/delivery', [PageController::class, 'delivery'])->name('pages.delivery');
Route::get('/payment', [PageController::class, 'payment'])->name('pages.payment');
Route::get('/faq', [PageController::class, 'faq'])->name('pages.faq');
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');

Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function (): void {
    Route::get('/', 'index')->name('index');
    Route::middleware('throttle:cart')->group(function (): void {
        Route::post('/items/{product}', 'store')->name('store');
        Route::patch('/items/{product}', 'update')->name('update');
        Route::delete('/items/{product}', 'destroy')->name('destroy');
        Route::delete('/clear', 'clear')->name('clear');
    });
});

Route::controller(CheckoutController::class)->group(function (): void {
    Route::get('/checkout', 'create')->name('checkout.create');
    Route::post('/checkout', 'store')->middleware('throttle:checkout')->name('checkout.store');
    Route::get('/checkout/success', 'success')->name('checkout.success');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/account/orders', [AccountOrderController::class, 'index'])->name('account.orders.index');
    Route::get('/account/orders/{order}', [AccountOrderController::class, 'show'])->name('account.orders.show');
    Route::post('/account/orders/{order}/repeat', [AccountOrderController::class, 'repeat'])->name('account.orders.repeat');
});

Route::middleware('auth')->group(function () {
    Route::prefix('account')->name('account.')->group(function (): void {
        Route::get('/favorites', [AccountFavoriteController::class, 'index'])->name('favorites.index');
        Route::post('/favorites/{product}', [AccountFavoriteController::class, 'store'])->name('favorites.store');
        Route::delete('/favorites/{product}', [AccountFavoriteController::class, 'destroy'])->name('favorites.destroy');
        Route::resource('addresses', AccountAddressController::class)->except('show');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function (): void {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/storefront', [AdminStorefrontSettingsController::class, 'edit'])->name('storefront.edit');
    Route::put('/storefront', [AdminStorefrontSettingsController::class, 'update'])->name('storefront.update');
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('collections', AdminCollectionController::class)->except('show');
    Route::resource('promos', AdminPromoBlockController::class)->except('show');
    Route::resource('farmers', \App\Http\Controllers\Admin\FarmerController::class)->except('show');
    Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class)->except('show');
    Route::resource('faq-items', \App\Http\Controllers\Admin\FaqItemController::class)->except('show');
    Route::resource('products', AdminProductController::class)->except('show');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
});

require __DIR__.'/auth.php';
