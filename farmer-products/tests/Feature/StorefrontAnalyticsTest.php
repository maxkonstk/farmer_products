<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Notifications\OrderPlacedNotification;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StorefrontAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_exposes_search_action_schema(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('SearchAction', false);
        $response->assertSee(route('catalog.index').'?q={search_term_string}', false);
        $response->assertSee('WebSite', false);
    }

    public function test_catalog_and_product_pages_emit_schema_and_analytics_payloads(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $product = Product::query()->firstOrFail();

        $catalogResponse = $this->get(route('catalog.index'));

        $catalogResponse->assertOk();
        $catalogResponse->assertSee('CollectionPage', false);
        $catalogResponse->assertSee('BreadcrumbList', false);
        $catalogResponse->assertSee('"event":"view_item_list"', false);
        $catalogResponse->assertSee('"item_list_id":"catalog"', false);

        $productResponse = $this->get(route('products.show', $product));

        $productResponse->assertOk();
        $productResponse->assertSee('"@type":"Product"', false);
        $productResponse->assertSee('BreadcrumbList', false);
        $productResponse->assertSee('"event":"view_item"', false);
        $productResponse->assertSee('"item_id":"'.$product->slug.'"', false);
    }

    public function test_add_to_cart_event_is_rendered_after_redirect(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $product = Product::query()->firstOrFail();

        $response = $this->followingRedirects()
            ->from(route('products.show', $product))
            ->post(route('cart.store', $product));

        $response->assertOk();
        $response->assertSee('"event":"add_to_cart"', false);
        $response->assertSee('"item_id":"'.$product->slug.'"', false);
    }

    public function test_checkout_pages_emit_begin_checkout_and_purchase_events(): void
    {
        Notification::fake();

        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $product = Product::query()->firstOrFail();

        $this->post(route('cart.store', $product))->assertRedirect();

        $checkoutResponse = $this->get(route('checkout.create'));

        $checkoutResponse->assertOk();
        $checkoutResponse->assertSee('"event":"begin_checkout"', false);
        $checkoutResponse->assertSee('"item_list_id":"checkout"', false);

        $submitResponse = $this->post(route('checkout.store'), [
            'customer_name' => 'Тестовый покупатель',
            'phone' => '+7 (927) 123-45-67',
            'email' => 'buyer@example.com',
            'fulfillment_method' => 'delivery',
            'delivery_window' => 'tomorrow-day',
            'substitution_preference' => 'call',
            'address' => 'г. Самара, ул. Лесная, д. 7',
            'comment' => 'Тестовое оформление заказа',
        ]);

        $submitResponse->assertSessionHasNoErrors();
        $submitResponse->assertRedirect(route('checkout.success'));

        $order = Order::query()->firstOrFail();

        $successResponse = $this->get(route('checkout.success'));

        $successResponse->assertOk();
        $successResponse->assertSee('"event":"purchase"', false);
        $successResponse->assertSee('"transaction_id":"'.$order->order_number.'"', false);

        Notification::assertSentOnDemand(OrderPlacedNotification::class);
    }
}
