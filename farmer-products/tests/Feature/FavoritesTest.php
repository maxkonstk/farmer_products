<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_add_and_remove_product_from_favorites(): void
    {
        $user = User::factory()->create();

        $category = Category::query()->create([
            'name' => 'Сыры',
            'slug' => 'cheese',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Качотта',
            'slug' => 'kachotta',
            'description' => 'Полутвердый сыр от локальной сыроварни.',
            'price' => 540,
            'stock' => 6,
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->post(route('account.favorites.store', $product))
            ->assertRedirect();

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($user)
            ->delete(route('account.favorites.destroy', $product))
            ->assertRedirect();

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_authenticated_user_can_open_favorites_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('account.favorites.index'))->assertOk();
    }
}
