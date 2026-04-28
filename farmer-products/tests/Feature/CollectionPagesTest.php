<?php

namespace Tests\Feature;

use App\Models\Collection;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CollectionSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectionPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_collection_page_renders_successfully(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);
        $this->seed(CollectionSeeder::class);

        $collection = Collection::query()->firstOrFail();

        $this->get(route('collections.show', $collection))->assertOk();
    }
}
