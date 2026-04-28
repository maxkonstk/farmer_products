<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_and_filter_orders(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        Order::query()->create([
            'customer_name' => 'Анна Смирнова',
            'phone' => '+7 999 111-22-33',
            'email' => 'anna@example.com',
            'address' => 'ул. Садовая, 10',
            'fulfillment_method' => 'delivery',
            'delivery_window' => 'weekday_evening',
            'total_price' => 2500,
            'status' => OrderStatus::NEW,
        ]);

        Order::query()->create([
            'customer_name' => 'Павел Иванов',
            'phone' => '+7 999 444-55-66',
            'email' => 'pavel@example.com',
            'address' => 'ул. Центральная, 5',
            'fulfillment_method' => 'pickup',
            'total_price' => 1800,
            'status' => OrderStatus::CONFIRMED,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index', [
            'q' => 'anna@example.com',
            'status' => OrderStatus::NEW->value,
            'fulfillment_method' => 'delivery',
        ]));

        $response->assertOk();
        $response->assertSee('Анна Смирнова');
        $response->assertDontSee('Павел Иванов');
    }

    public function test_admin_can_update_order_status(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $order = Order::query()->create([
            'customer_name' => 'Мария Орлова',
            'phone' => '+7 999 777-88-99',
            'email' => 'maria@example.com',
            'address' => 'пр. Победы, 4',
            'fulfillment_method' => 'delivery',
            'total_price' => 3200,
            'status' => OrderStatus::NEW,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
            'status' => OrderStatus::CONFIRMED->value,
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $this->assertSame(OrderStatus::CONFIRMED, $order->fresh()->status);
    }
}
