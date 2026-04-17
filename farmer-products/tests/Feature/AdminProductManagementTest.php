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
}
