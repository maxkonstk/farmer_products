<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontPerformanceHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_images_render_explicit_dimensions_and_sizes(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $product = Product::query()->firstOrFail();

        $homeResponse = $this->get(route('home'));

        $homeResponse->assertOk();
        $homeResponse->assertSee('/images/hero/farm-market.jpg', false);
        $homeResponse->assertSee('width="1200"', false);
        $homeResponse->assertSee('height="900"', false);
        $homeResponse->assertSee('sizes="(max-width: 900px) 100vw, 46vw"', false);
        $homeResponse->assertSee('sizes="(max-width: 640px) 100vw, (max-width: 1100px) 50vw, 33vw"', false);

        $productResponse = $this->get(route('products.show', $product));

        $productResponse->assertOk();
        $productResponse->assertSee('sizes="(max-width: 900px) 100vw, 48vw"', false);
        $productResponse->assertSee('sizes="(max-width: 900px) 33vw, 16vw"', false);
    }

    public function test_catalog_checkout_and_address_forms_expose_mobile_friendly_autocomplete(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $product = Product::query()->firstOrFail();
        $user = User::factory()->create();

        $catalogResponse = $this->get(route('catalog.index'));

        $catalogResponse->assertOk();
        $catalogResponse->assertSee('type="search"', false);
        $catalogResponse->assertSee('inputmode="search"', false);
        $catalogResponse->assertSee('enterkeyhint="search"', false);

        $this->post(route('cart.store', $product))->assertRedirect();

        $checkoutResponse = $this->get(route('checkout.create'));

        $checkoutResponse->assertOk();
        $checkoutResponse->assertSee('autocomplete="name"', false);
        $checkoutResponse->assertSee('autocomplete="tel"', false);
        $checkoutResponse->assertSee('autocomplete="email"', false);
        $checkoutResponse->assertSee('autocomplete="street-address"', false);

        $addressResponse = $this->actingAs($user)->get(route('account.addresses.create'));

        $addressResponse->assertOk();
        $addressResponse->assertSee('autocomplete="address-level2"', false);
        $addressResponse->assertSee('autocomplete="shipping name"', false);
        $addressResponse->assertSee('autocomplete="shipping street-address"', false);
        $addressResponse->assertSee('inputmode="tel"', false);
    }
}
