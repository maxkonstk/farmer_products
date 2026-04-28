<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepeatOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_repeat_previous_order_into_cart(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $category = Category::query()->create([
            'name' => 'Овощи',
            'slug' => 'vegetables',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Тестовый товар',
            'slug' => 'testovyj-tovar',
            'description' => 'Подробное описание тестового товара для повторного заказа.',
            'price' => 100,
            'stock' => 4,
            'is_active' => true,
        ]);

        $order = Order::query()->create([
            'user_id' => $user->id,
            'customer_name' => 'Покупатель',
            'phone' => '+7 (900) 000-00-00',
            'email' => 'buyer@example.com',
            'address' => 'Самара',
            'fulfillment_method' => 'delivery',
            'total_price' => 200,
            'status' => OrderStatus::NEW,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 2,
            'price' => 100,
        ]);

        $response = $this->actingAs($user)->post(route('account.orders.repeat', $order));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success');
        $this->assertSame(2, session('cart.items')[$product->id] ?? null);
    }
}
