<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AddressBookTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_store_a_default_address(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('account.addresses.store'), [
            'label' => 'Дом',
            'recipient_name' => 'Иван Петров',
            'phone' => '+7 (927) 123-45-67',
            'city' => 'Самара',
            'address_line' => 'ул. Молодогвардейская, д. 20, кв. 15',
            'comment' => 'Домофон 15',
            'is_default' => '1',
        ]);

        $response->assertRedirect(route('account.addresses.index'));
        $this->assertDatabaseHas('customer_addresses', [
            'user_id' => $user->id,
            'label' => 'Дом',
            'is_default' => true,
        ]);
    }

    public function test_checkout_can_use_saved_address_for_authenticated_user(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $category = Category::query()->create([
            'name' => 'Молочные продукты',
            'slug' => 'dairy',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Фермерское молоко',
            'slug' => 'fermerskoe-moloko',
            'description' => 'Свежая партия от локального хозяйства.',
            'price' => 180,
            'stock' => 8,
            'is_active' => true,
        ]);

        $address = CustomerAddress::query()->create([
            'user_id' => $user->id,
            'label' => 'Дом',
            'city' => 'Самара',
            'address_line' => 'ул. Садовая, д. 12',
            'comment' => 'Подъезд 2',
            'is_default' => true,
        ]);

        $this->post(route('cart.store', $product))->assertRedirect();

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'customer_name' => 'Иван Петров',
            'phone' => '+7 (927) 123-45-67',
            'email' => 'ivan@example.com',
            'fulfillment_method' => 'delivery',
            'saved_address_id' => $address->id,
        ]);

        $response->assertRedirect(route('checkout.success'));

        $order = Order::query()->latest()->firstOrFail();

        $this->assertSame('Самара, ул. Садовая, д. 12, Подъезд 2', $order->address);
    }
}
