<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\FaqItem;
use App\Models\Farmer;
use App\Models\Order;
use App\Models\Product;
use App\Models\PromoBlock;
use App\Models\Testimonial;
use App\Services\HealthCheckService;
use App\Services\StorefrontSettingsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly StorefrontSettingsService $storefrontSettings,
        private readonly HealthCheckService $healthChecks,
    )
    {
    }

    public function index(): View
    {
        $stats = [
            'categories' => Category::query()->count(),
            'products' => Product::query()->count(),
            'collections' => Collection::query()->count(),
            'orders' => Order::query()->count(),
            'new_orders' => Order::query()->where('status', 'new')->count(),
            'revenue' => (float) Order::query()->sum('total_price'),
            'farmers' => Farmer::query()->count(),
            'testimonials' => Testimonial::query()->count(),
            'faq_items' => FaqItem::query()->count(),
            'promo_blocks' => PromoBlock::query()->count(),
            'active_promos' => PromoBlock::query()->published()->activeWindow()->count(),
        ];

        $recentOrders = Order::query()
            ->withCount('items')
            ->latest()
            ->take(6)
            ->get();

        $lowStockProducts = Product::query()
            ->with('category')
            ->where('stock', '<=', Product::LOW_STOCK_THRESHOLD)
            ->orderBy('stock')
            ->take(6)
            ->get();

        $analytics = $this->storefrontSettings->analytics();
        $readiness = $this->healthChecks->readinessReport();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts', 'analytics', 'readiness'));
    }
}
