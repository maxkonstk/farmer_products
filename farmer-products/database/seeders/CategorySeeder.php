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
                'description' => 'Овощи и корнеплоды короткого хранения от теплиц и небольших хозяйств Удмуртии.',
                'image' => '/images/products/vegetables.svg',
            ],
            [
                'name' => 'Фрукты',
                'slug' => 'fruits',
                'description' => 'Сезонные фрукты для ежедневной корзины, завтраков и домашних десертов.',
                'image' => '/images/products/fruits.svg',
            ],
            [
                'name' => 'Ягоды',
                'slug' => 'berries',
                'description' => 'Ягоды с быстрой отгрузкой: для свежего потребления, выпечки и домашних заготовок.',
                'image' => '/images/products/fruits.svg',
            ],
            [
                'name' => 'Зелень и салаты',
                'slug' => 'greens',
                'description' => 'Хрустящие салаты и пряные травы, которые лучше всего покупать под ближайшую готовку.',
                'image' => '/images/products/vegetables.svg',
            ],
            [
                'name' => 'Молочная продукция',
                'slug' => 'dairy',
                'description' => 'Молочные продукты с коротким составом и понятным происхождением от семейных ферм.',
                'image' => '/images/products/dairy.svg',
            ],
            [
                'name' => 'Сыры и масло',
                'slug' => 'cheese',
                'description' => 'Ремесленные сыры, масло и соленые молочные продукты для повседневной и гастрономичной кухни.',
                'image' => '/images/products/dairy.svg',
            ],
            [
                'name' => 'Мясо и птица',
                'slug' => 'meat',
                'description' => 'Охлажденное мясо и птица с подтвержденной поставкой и холодовой цепочкой.',
                'image' => '/images/products/meat.svg',
            ],
            [
                'name' => 'Мед и пасека',
                'slug' => 'honey',
                'description' => 'Натуральный мед и продукты пасеки с указанием сорта, региона и сезона сбора.',
                'image' => '/images/products/honey.svg',
            ],
            [
                'name' => 'Домашняя выпечка',
                'slug' => 'bakery',
                'description' => 'Хлеб, пироги и утренняя выпечка, которую лучше бронировать заранее под доставку.',
                'image' => '/images/products/bakery.svg',
            ],
            [
                'name' => 'Варенье и заготовки',
                'slug' => 'preserves',
                'description' => 'Домашние заготовки из сезонного урожая: джемы, соленья и банки для кладовой.',
                'image' => '/images/products/bakery.svg',
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
