<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AnalyticsService
{
    private const SESSION_KEY = 'analytics.events';

    /**
     * @return array<int, array<string, mixed>>
     */
    public function initialEvents(): array
    {
        /** @var mixed $events */
        $events = session(self::SESSION_KEY, []);

        if (! is_array($events)) {
            return [];
        }

        return array_values(array_filter($events, 'is_array'));
    }

    /**
     * @param  array<string, mixed>  $event
     */
    public function flashEvent(array $event): void
    {
        $events = $this->initialEvents();
        $events[] = $event;

        session()->flash(self::SESSION_KEY, $events);
    }

    /**
     * @return array<string, mixed>
     */
    public function productItem(
        Product|OrderItem $line,
        int $quantity = 1,
        ?string $listId = null,
        ?string $listName = null,
    ): array {
        if ($line instanceof Product) {
            $item = [
                'item_id' => $line->slug,
                'item_name' => $line->name,
                'item_brand' => config('shop.brand.name', config('app.name')),
                'item_category' => $line->category?->name,
                'item_category2' => $line->collections->first()?->name,
                'item_variant' => $line->weight,
                'item_list_id' => $listId,
                'item_list_name' => $listName,
                'price' => $this->money($line->price),
                'quantity' => $quantity,
            ];

            return $this->clean($item);
        }

        $product = $line->product;

        $item = [
            'item_id' => $product?->slug ?? 'product-'.($line->product_id ?: Str::slug($line->product_name)),
            'item_name' => $line->product_name,
            'item_brand' => config('shop.brand.name', config('app.name')),
            'item_category' => $product?->category?->name,
            'item_category2' => $product?->collections->first()?->name,
            'item_variant' => $product?->weight,
            'price' => $this->money($line->price),
            'quantity' => $quantity,
        ];

        return $this->clean($item);
    }

    /**
     * @param  iterable<Product>  $products
     * @return array<int, array<string, mixed>>
     */
    public function itemsFromProducts(iterable $products, ?string $listId = null, ?string $listName = null): array
    {
        return collect($products)
            ->values()
            ->map(fn (Product $product) => $this->productItem($product, 1, $listId, $listName))
            ->all();
    }

    /**
     * @param  Collection<int, array{product: Product, quantity: int, line_total: float}>  $items
     * @return array<int, array<string, mixed>>
     */
    public function itemsFromCart(Collection $items, ?string $listId = 'cart', ?string $listName = 'Корзина'): array
    {
        return $items
            ->values()
            ->map(fn (array $item) => $this->productItem($item['product'], $item['quantity'], $listId, $listName))
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function itemsFromOrder(Order $order): array
    {
        $order->loadMissing('items.product.category', 'items.product.collections');

        return $order->items
            ->map(fn (OrderItem $item) => $this->productItem($item, $item->quantity))
            ->all();
    }

    private function money(mixed $value): float
    {
        return round((float) $value, 2);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function clean(array $payload): array
    {
        return collect($payload)
            ->reject(fn ($value) => $value === null || $value === '')
            ->all();
    }
}
