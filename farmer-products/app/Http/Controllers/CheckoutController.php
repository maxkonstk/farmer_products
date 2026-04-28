<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderService $orderService,
    ) {
    }

    public function create(): View|RedirectResponse
    {
        $summary = $this->cartService->summary();

        if ($summary['is_empty']) {
            return redirect()->route('catalog.index')->with('warning', 'Корзина пуста. Добавьте товары перед оформлением заказа.');
        }

        $user = auth()->user();
        $savedAddresses = collect();
        $defaultAddress = null;

        if ($user) {
            $savedAddresses = $user->addresses()
                ->orderByDesc('is_default')
                ->latest()
                ->get();

            /** @var CustomerAddress|null $defaultAddress */
            $defaultAddress = $savedAddresses->firstWhere('is_default', true) ?? $savedAddresses->first();
        }

        return view('checkout.create', [
            ...$summary,
            'user' => $user,
            'savedAddresses' => $savedAddresses,
            'defaultAddress' => $defaultAddress,
        ]);
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $order = $this->orderService->place($request->validated(), $request->user());

        session(['checkout.last_order_id' => $order->id]);

        return redirect()->route('checkout.success')->with('success', 'Заказ успешно оформлен.');
    }

    public function success(): View|RedirectResponse
    {
        $orderId = session()->pull('checkout.last_order_id');

        if (! $orderId) {
            return redirect()->route('home');
        }

        $order = Order::query()
            ->with(['items.product', 'user'])
            ->findOrFail($orderId);

        return view('checkout.success', compact('order'));
    }
}
