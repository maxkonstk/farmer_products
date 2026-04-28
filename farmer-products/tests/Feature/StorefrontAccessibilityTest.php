<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_layout_exposes_skip_link_and_main_landmark(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('href="#main-content"', false);
        $response->assertSee('<main id="main-content"', false);
        $response->assertSee('aria-controls="site-mobile-menu"', false);
    }

    public function test_catalog_and_checkout_render_mobile_accessibility_controls(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $product = Product::query()->firstOrFail();

        $catalogResponse = $this->get(route('catalog.index', [
            'q' => 'молоко',
            'availability' => 'in-stock',
        ]));

        $catalogResponse->assertOk();
        $catalogResponse->assertSee('catalog-filters', false);
        $catalogResponse->assertSee('aria-controls="catalog-filters"', false);

        $this->post(route('cart.store', $product))->assertRedirect();

        $checkoutResponse = $this->get(route('checkout.create'));

        $checkoutResponse->assertOk();
        $checkoutResponse->assertSee('id="checkout-form"', false);
        $checkoutResponse->assertSee('form="checkout-form"', false);
        $checkoutResponse->assertSee('id="address-hint"', false);
        $checkoutResponse->assertSee('mobile-summary-bar', false);
    }
}
