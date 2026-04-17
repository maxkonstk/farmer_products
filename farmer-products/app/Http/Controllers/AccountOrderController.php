<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AccountOrderController extends Controller
{
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
}
