<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function place(array $data, ?User $user = null): Order
    {
        $cart = $this->cartService->raw();

        if ($cart === []) {
            throw ValidationException::withMessages([
                'cart' => 'Корзина пуста. Добавьте товары перед оформлением заказа.',
            ]);
        }

        $order = DB::transaction(function () use ($cart, $data, $user): Order {
            $products = Product::query()
                ->whereIn('id', array_keys($cart))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $items = collect($cart)->map(function (int $quantity, int $productId) use ($products): array {
                /** @var Product|null $product */
                $product = $products->get($productId);

                if (! $product || ! $product->is_active) {
                    throw ValidationException::withMessages([
                        'cart' => 'Один из товаров больше недоступен для заказа. Проверьте корзину.',
                    ]);
                }

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'stock' => "Недостаточно товара «{$product->name}» на складе.",
                    ]);
                }

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => (float) $product->price,
                    'line_total' => (float) $product->price * $quantity,
                ];
            });

            $order = Order::create([
                'user_id' => $user?->id,
                'customer_name' => $data['customer_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $this->resolveAddress($data, $user),
                'fulfillment_method' => $data['fulfillment_method'],
                'delivery_window' => $data['delivery_window'] ?? null,
                'substitution_preference' => $data['substitution_preference'] ?? null,
                'comment' => $data['comment'] ?? null,
                'total_price' => $items->sum('line_total'),
                'status' => OrderStatus::NEW,
            ]);

            $items->each(function (array $item) use ($order): void {
                /** @var Product $product */
                $product = $item['product'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $product->decrement('stock', $item['quantity']);
            });

            $this->cartService->clear();

            return $order->load(['items.product', 'user']);
        });

        Notification::route('mail', $order->email)
            ->notify(new OrderPlacedNotification($order));

        return $order;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveAddress(array $data, ?User $user): string
    {
        if ($data['fulfillment_method'] === 'pickup') {
            return (string) config('shop.delivery.pickup_address');
        }

        if (filled($data['address'] ?? null)) {
            return trim((string) $data['address']);
        }

        if ($user && filled($data['saved_address_id'] ?? null)) {
            /** @var CustomerAddress|null $savedAddress */
            $savedAddress = $user->addresses()->find($data['saved_address_id']);

            if ($savedAddress) {
                return $savedAddress->formatted_address;
            }
        }

        throw ValidationException::withMessages([
            'address' => 'Не удалось определить адрес доставки.',
        ]);
    }
}
