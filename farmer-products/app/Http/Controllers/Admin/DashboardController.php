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
use Illuminate\View\View;

class DashboardController extends Controller
{
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
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }
}
