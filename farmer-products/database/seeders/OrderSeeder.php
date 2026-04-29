<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $buyer = User::query()->firstWhere('email', 'buyer@farmer-shop.test');

        if (! $buyer) {
            return;
        }

        $this->createOrder(
            $buyer,
            [
                ['slug' => 'moloko-fermerskoe-1l', 'quantity' => 2],
                ['slug' => 'med-cvetochnyy-naturalnyy', 'quantity' => 1],
            ],
            OrderStatus::CONFIRMED,
            'г. Ижевск, ул. Молодежная, д. 12, кв. 8'
        );

        $this->createOrder(
            $buyer,
            [
                ['slug' => 'hleb-domashniy-celnozernovoy', 'quantity' => 2],
                ['slug' => 'yayca-kurinye-derevenskie', 'quantity' => 1],
                ['slug' => 'yabloki-sezonnye', 'quantity' => 1],
            ],
            OrderStatus::COMPLETED,
            'г. Ижевск, ул. Полевая, д. 7'
        );
    }

    /**
     * @param  array<int, array{slug:string,quantity:int}>  $positions
     */
    private function createOrder(User $buyer, array $positions, OrderStatus $status, string $address): void
    {
        $existing = Order::query()
            ->where('user_id', $buyer->id)
            ->where('address', $address)
            ->first();

        if ($existing) {
            return;
        }

        $products = collect($positions)
            ->map(function (array $position): ?array {
                $product = Product::query()->firstWhere('slug', $position['slug']);

                if (! $product) {
                    return null;
                }

                return [
                    'product' => $product,
                    'quantity' => $position['quantity'],
                    'price' => (float) $product->price,
                ];
            })
            ->filter()
            ->values();

        if ($products->isEmpty()) {
            return;
        }

        $order = Order::query()->create([
            'user_id' => $buyer->id,
            'customer_name' => $buyer->name,
            'phone' => $buyer->phone ?? '+7 (900) 000-00-00',
            'email' => $buyer->email,
            'address' => $address,
            'comment' => 'Тестовый заказ, созданный сидером.',
            'status' => $status,
            'total_price' => $products->sum(fn (array $item) => $item['price'] * $item['quantity']),
        ]);

        $products->each(function (array $item) use ($order): void {
            $order->items()->create([
                'product_id' => $item['product']->id,
                'product_name' => $item['product']->name,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        });
    }
}
