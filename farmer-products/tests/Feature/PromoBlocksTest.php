<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\PromoBlock;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CollectionSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoBlocksTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_and_catalog_render_active_promo_blocks(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);
        $this->seed(CollectionSeeder::class);

        $collection = Collection::query()->firstOrFail();
        $category = Category::query()->firstOrFail();
        $product = Product::query()->firstOrFail();

        PromoBlock::query()->create([
            'name' => 'Домашний промо-блок',
            'title' => 'Промо для главной',
            'body' => 'Показываем, что merchandising живет отдельно от хардкода и может меняться через админку.',
            'placement' => 'home',
            'theme' => 'wheat',
            'is_published' => true,
            'collection_id' => $collection->id,
        ]);

        PromoBlock::query()->create([
            'name' => 'Каталожный промо-блок',
            'title' => 'Промо для каталога',
            'body' => 'Это промо должно появиться в каталоге как живой merchandising-слой.',
            'placement' => 'catalog',
            'theme' => 'sage',
            'is_published' => true,
            'category_id' => $category->id,
            'product_id' => null,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Промо для главной')
            ->assertSee(route('collections.show', $collection), false);

        $this->get(route('catalog.index', ['category' => $category->slug]))
            ->assertOk()
            ->assertSee('Промо для каталога')
            ->assertSee(route('categories.show', $category), false);

        $this->get(route('products.show', $product))->assertOk();
    }
}
