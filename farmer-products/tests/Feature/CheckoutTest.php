<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Notifications\OrderPlacedNotification;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_place_an_order_from_cart(): void
    {
        Notification::fake();

        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $product = \App\Models\Product::query()->firstOrFail();

        $this->post(route('cart.store', $product))->assertRedirect();

        $response = $this->post(route('checkout.store'), [
            'customer_name' => 'Тестовый покупатель',
            'phone' => '+7 (3412) 12-34-56',
            'email' => 'buyer@example.com',
            'fulfillment_method' => 'delivery',
            'delivery_window' => 'tomorrow-day',
            'substitution_preference' => 'call',
            'address' => 'г. Ижевск, ул. Лесная, д. 7',
            'comment' => 'Тестовое оформление заказа',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertSame(route('checkout.success'), $response->headers->get('Location'));

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);

        $order = Order::query()->firstOrFail();

        $this->assertNotNull($order->order_number);
        $this->assertStringStartsWith('FL-', $order->order_number);
        $this->assertSame('delivery', $order->fulfillment_method);
        $this->assertSame('tomorrow-day', $order->delivery_window);
        $this->assertSame('call', $order->substitution_preference);

        Notification::assertSentOnDemand(OrderPlacedNotification::class);
    }
}
