<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Овощи',
                'slug' => 'vegetables',
                'description' => 'Свежие сезонные овощи от местных фермерских хозяйств.',
                'image' => '/images/categories/vegetables.jpg',
            ],
            [
                'name' => 'Фрукты',
                'slug' => 'fruits',
                'description' => 'Сладкие и ароматные фрукты без лишней обработки.',
                'image' => '/images/categories/fruits.jpg',
            ],
            [
                'name' => 'Ягоды',
                'slug' => 'berries',
                'description' => 'Сезонные ягоды для десертов, завтраков и натуральных заготовок.',
                'image' => '/images/categories/berries.jpg',
            ],
            [
                'name' => 'Зелень и салаты',
                'slug' => 'greens',
                'description' => 'Свежая зелень, салаты и пряные травы для повседневной кухни.',
                'image' => '/images/categories/greens.jpg',
            ],
            [
                'name' => 'Молочная продукция',
                'slug' => 'dairy',
                'description' => 'Натуральное молоко, творог, сметана, кефир и ряженка.',
                'image' => '/images/categories/dairy.jpg',
            ],
            [
                'name' => 'Сыры и масло',
                'slug' => 'cheese',
                'description' => 'Фермерские сыры, масло и мягкие сырные продукты ручной работы.',
                'image' => '/images/categories/cheese.jpg',
            ],
            [
                'name' => 'Мясо и птица',
                'slug' => 'meat',
                'description' => 'Охлажденное мясо и птица с понятным происхождением.',
                'image' => '/images/categories/meat.jpg',
            ],
            [
                'name' => 'Мед и пасека',
                'slug' => 'honey',
                'description' => 'Фермерский мед разных сортов и натуральные продукты пчеловодства.',
                'image' => '/images/categories/honey.jpg',
            ],
            [
                'name' => 'Домашняя выпечка',
                'slug' => 'bakery',
                'description' => 'Хлеб и выпечка, приготовленные по домашним рецептам.',
                'image' => '/images/categories/bakery.jpg',
            ],
            [
                'name' => 'Варенье и заготовки',
                'slug' => 'preserves',
                'description' => 'Домашние джемы, соленья и заготовки из сезонных продуктов.',
                'image' => '/images/categories/preserves.jpg',
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
