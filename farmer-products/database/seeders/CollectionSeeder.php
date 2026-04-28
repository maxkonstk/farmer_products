<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::query()->get()->keyBy('name');

        $collections = [
            [
                'name' => 'Сезонная корзина недели',
                'description' => 'Подборка для первой покупки: овощи, молочка, выпечка и базовые позиции, которые лучше всего показывают формат лавки.',
                'intro' => 'Стартовая подборка с товарами, которые чаще всего берут в первый или повторный заказ.',
                'badge' => 'Хит недели',
                'image' => '/images/products/hero-farm.svg',
                'is_featured' => true,
                'sort_order' => 10,
                'product_names' => ['Молоко фермерское 1 л', 'Яйца куриные деревенские', 'Хлеб домашний цельнозерновой', 'Мед цветочный натуральный', 'Огурцы свежие'],
            ],
            [
                'name' => 'Завтрак без маркетплейса',
                'description' => 'Все для спокойного утреннего набора: сыры, молочка, яйца, мед и свежая выпечка без компромиссов по составу.',
                'intro' => 'Набор для кухни, где важны понятный вкус, короткий состав и быстрая повторяемость заказа.',
                'badge' => 'Утренний набор',
                'image' => '/images/products/dairy.svg',
                'is_featured' => true,
                'sort_order' => 20,
                'product_names' => ['Молоко фермерское 1 л', 'Творог фермерский', 'Сыр козий мягкий', 'Яйца куриные деревенские', 'Хлеб домашний цельнозерновой'],
            ],
            [
                'name' => 'Готово к ужину',
                'description' => 'Подборка для ужина в один заказ: мясо, овощи, зелень и продукты, которые легко комбинировать без долгой подготовки.',
                'intro' => 'Быстрый способ собрать полноценную корзину для ужина с локальными продуктами.',
                'badge' => 'На вечер',
                'image' => '/images/products/meat.svg',
                'is_featured' => false,
                'sort_order' => 30,
                'product_names' => ['Бедро индейки фермерское', 'Картофель деревенский', 'Укроп свежий', 'Помидоры тепличные'],
            ],
        ];

        foreach ($collections as $payload) {
            $productIds = collect($payload['product_names'])
                ->map(fn (string $name) => $products->get($name)?->id)
                ->filter()
                ->values()
                ->all();

            unset($payload['product_names']);

            $collection = Collection::query()->updateOrCreate(
                ['name' => $payload['name']],
                $payload
            );

            $collection->products()->sync($productIds);
        }
    }
}
