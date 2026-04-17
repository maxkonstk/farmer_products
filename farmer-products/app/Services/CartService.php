<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'cart.items';

    /**
     * @return array<int, int>
     */
    public function raw(): array
    {
        /** @var array<int, int> $cart */
        $cart = session(self::SESSION_KEY, []);

        return collect($cart)
            ->mapWithKeys(fn ($quantity, $productId) => [(int) $productId => max(0, (int) $quantity)])
            ->filter()
            ->all();
    }

    public function summary(): array
    {
        $items = $this->items();

        return [
            'items' => $items,
            'total_quantity' => (int) $items->sum('quantity'),
            'total_price' => (float) $items->sum('line_total'),
            'is_empty' => $items->isEmpty(),
        ];
    }

    public function items(): Collection
    {
        $cart = $this->raw();

        if ($cart === []) {
            return collect();
        }

        $products = Product::query()
            ->with('category')
            ->whereIn('id', array_keys($cart))
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $items = collect($cart)
            ->map(function (int $quantity, int $productId) use ($products): ?array {
                /** @var Product|null $product */
                $product = $products->get($productId);

                if (! $product) {
                    return null;
                }

                $actualQuantity = min($quantity, $product->stock);

                if ($actualQuantity < 1) {
                    return null;
                }

                return [
                    'product' => $product,
                    'quantity' => $actualQuantity,
                    'line_total' => $actualQuantity * (float) $product->price,
                ];
            })
            ->filter()
            ->values();

        $normalized = $items
            ->mapWithKeys(fn (array $item) => [$item['product']->id => $item['quantity']])
            ->all();

        if ($normalized !== $cart) {
            $this->store($normalized);
        }

        return $items;
    }

    /**
     * @return array{requested:int,actual:int,adjusted:bool}
     */
    public function add(Product $product, int $quantity = 1): array
    {
        $cart = $this->raw();
        $current = $cart[$product->id] ?? 0;
        $requested = $current + max(1, $quantity);
        $actual = min($requested, $product->stock);

        if ($actual > 0) {
            $cart[$product->id] = $actual;
        }

        $this->store($cart);

        return [
            'requested' => $requested,
            'actual' => $actual,
            'adjusted' => $requested !== $actual,
        ];
    }

    /**
     * @return array{requested:int,actual:int,adjusted:bool}
     */
    public function update(Product $product, int $quantity): array
    {
        $cart = $this->raw();
        $requested = max(0, $quantity);

        if ($requested === 0) {
            unset($cart[$product->id]);
            $this->store($cart);

            return [
                'requested' => 0,
                'actual' => 0,
                'adjusted' => false,
            ];
        }

        $actual = min($requested, $product->stock);

        if ($actual > 0) {
            $cart[$product->id] = $actual;
        } else {
            unset($cart[$product->id]);
        }

        $this->store($cart);

        return [
            'requested' => $requested,
            'actual' => $actual,
            'adjusted' => $requested !== $actual,
        ];
    }

    public function remove(Product $product): void
    {
        $cart = $this->raw();
        unset($cart[$product->id]);

        $this->store($cart);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return (int) collect($this->raw())->sum();
    }

    /**
     * @param  array<int, int>  $cart
     */
    private function store(array $cart): void
    {
        session([self::SESSION_KEY => $cart]);
    }
}
