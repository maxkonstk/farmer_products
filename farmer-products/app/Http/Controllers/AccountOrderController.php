<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AccountOrderController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index(): View
    {
        $orders = auth()->user()
            ->orders()
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('account.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), Response::HTTP_NOT_FOUND);

        $order->load(['items.product']);

        return view('account.orders.show', compact('order'));
    }

    public function repeat(Order $order): RedirectResponse
    {
        abort_unless($order->user_id === auth()->id(), Response::HTTP_NOT_FOUND);

        $result = $this->cartService->replaceWithOrder($order);

        if ($result['added_items'] === 0) {
            return redirect()->route('cart.index')->with('warning', 'Не удалось собрать корзину по этому заказу: товары больше недоступны.');
        }

        $message = "Корзина обновлена по заказу {$order->order_number}.";

        if ($result['adjusted_items'] > 0 || $result['skipped_items'] > 0) {
            $message .= ' Часть позиций была скорректирована по текущему остатку.';
        }

        return redirect()->route('cart.index')->with('success', $message);
    }
}
