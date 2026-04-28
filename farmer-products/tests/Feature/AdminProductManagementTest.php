<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product_with_uploaded_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $category = Category::query()->create([
            'name' => 'Тестовая категория',
            'description' => 'Категория для проверки загрузки файлов.',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Тестовый продукт',
            'description' => 'Достаточно длинное описание тестового продукта для успешной валидации формы.',
            'price' => 199.90,
            'weight' => '500 г',
            'stock' => 12,
            'is_active' => '1',
            'is_featured' => '1',
            'image_file' => UploadedFile::fake()->image('product.jpg'),
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::query()->firstOrFail();

        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_admin_can_filter_products_by_operational_signals(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $vegetables = Category::query()->create([
            'name' => 'Овощи',
            'description' => 'Свежие овощи.',
        ]);

        $dairy = Category::query()->create([
            'name' => 'Молочные продукты',
            'description' => 'Фермерская молочка.',
        ]);

        Product::query()->create([
            'category_id' => $vegetables->id,
            'name' => 'Скрытая морковь',
            'description' => 'Товар скрыт, но требует контроля по остатку.',
            'price' => 120,
            'stock' => 3,
            'is_active' => false,
            'is_featured' => false,
            'producer_name' => 'Полевая ферма',
            'origin_location' => 'Сызранский район',
        ]);

        Product::query()->create([
            'category_id' => $dairy->id,
            'name' => 'Молоко утреннее',
            'description' => 'Базовый товар в наличии.',
            'price' => 180,
            'stock' => 14,
            'is_active' => true,
            'is_featured' => true,
            'producer_name' => 'Семейная ферма',
            'origin_location' => 'Красноярский район',
        ]);

        Product::query()->create([
            'category_id' => $vegetables->id,
            'name' => 'Свекла пасечника',
            'description' => 'Нужна для проверки поиска по поставщику.',
            'price' => 90,
            'stock' => 0,
            'is_active' => true,
            'is_featured' => false,
            'producer_name' => 'Медовая долина',
            'origin_location' => 'Кинельский район',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.products.index', [
            'category_id' => $vegetables->id,
            'visibility' => 'hidden',
            'stock_state' => 'low',
        ]));

        $response->assertOk();
        $response->assertSee('Скрытая морковь');
        $response->assertDontSee('Молоко утреннее');
        $response->assertDontSee('Свекла пасечника');

        $searchResponse = $this->actingAs($admin)->get(route('admin.products.index', [
            'q' => 'Медовая долина',
            'stock_state' => 'out',
        ]));

        $searchResponse->assertOk();
        $searchResponse->assertSee('Свекла пасечника');
        $searchResponse->assertDontSee('Скрытая морковь');
    }
}
