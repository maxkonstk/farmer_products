<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCartRequest;
use App\Models\Product;
use App\Services\AnalyticsService;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly AnalyticsService $analytics,
    ) {
    }

    public function index(): View
    {
        return view('cart.index', $this->cartService->summary());
    }

    public function store(Product $product): RedirectResponse
    {
        if (! $product->is_active || $product->stock < 1) {
            return back()->with('error', 'Товар временно недоступен для заказа.');
        }

        $previousQuantity = $this->cartService->raw()[$product->id] ?? 0;
        $result = $this->cartService->add($product);
        $addedQuantity = max(0, $result['actual'] - $previousQuantity);

        if ($addedQuantity > 0) {
            $product->loadMissing(['category', 'collections']);

            $this->analytics->flashEvent([
                'event' => 'add_to_cart',
                'ecommerce' => [
                    'currency' => 'RUB',
                    'value' => round((float) $product->price * $addedQuantity, 2),
                    'items' => [
                        $this->analytics->productItem($product, $addedQuantity, 'product-card', 'Добавление в корзину'),
                    ],
                ],
            ]);
        }

        return back()->with(
            $result['adjusted'] ? 'warning' : 'success',
            $result['adjusted']
                ? 'Количество товара в корзине скорректировано с учетом остатка на складе.'
                : 'Товар добавлен в корзину.'
        );
    }

    public function update(UpdateCartRequest $request, Product $product): RedirectResponse
    {
        $result = $this->cartService->update($product, (int) $request->validated('quantity'));

        if ($result['actual'] === 0) {
            return redirect()->route('cart.index')->with('warning', 'Товар удален из корзины.');
        }

        return redirect()->route('cart.index')->with(
            $result['adjusted'] ? 'warning' : 'success',
            $result['adjusted']
                ? 'Количество товара уменьшено до доступного остатка.'
                : 'Корзина обновлена.'
        );
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->cartService->remove($product);

        return redirect()->route('cart.index')->with('success', 'Товар удален из корзины.');
    }

    public function clear(): RedirectResponse
    {
        $this->cartService->clear();

        return redirect()->route('cart.index')->with('success', 'Корзина очищена.');
    }
}
