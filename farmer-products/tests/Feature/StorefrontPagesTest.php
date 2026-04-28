<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_trust_pages_and_product_page_render_successfully(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $product = Product::query()->firstOrFail();

        $this->get(route('pages.about'))->assertOk();
        $this->get(route('pages.contacts'))->assertOk();
        $this->get(route('pages.delivery'))->assertOk();
        $this->get(route('pages.payment'))->assertOk();
        $this->get(route('pages.faq'))->assertOk();
        $this->get(route('products.show', $product))->assertOk();
    }
}
