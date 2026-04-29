<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\PromoBlock;
use Illuminate\Database\Seeder;

class PromoBlockSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = Category::query()->pluck('id', 'slug');
        $collectionIds = Collection::query()->pluck('id', 'slug');
        $productIds = Product::query()->pluck('id', 'slug');

        $promos = [
            [
                'name' => 'Стартовая корзина недели',
                'eyebrow' => 'Сценарий недели',
                'badge' => 'Для первого заказа',
                'title' => 'Начните с сезонной корзины, которую удобно повторять каждую неделю',
                'body' => 'Собрали подборку из молочки, овощей, базовой выпечки и хитов витрины. Это не случайный набор, а хороший первый сценарий для знакомства с форматом магазина.',
                'cta_label' => 'Открыть стартовую корзину',
                'image' => '/images/hero/farm-market.jpg',
                'theme' => 'wheat',
                'placement' => 'home',
                'sort_order' => 10,
                'is_published' => true,
                'collection_slug' => 'sezonnaya-korzina-nedeli',
            ],
            [
                'name' => 'Утренний набор без маркетплейса',
                'eyebrow' => 'Повторная покупка',
                'badge' => 'Завтрак без шума',
                'title' => 'Молочка, сыр, яйца и хлеб, которые удобно брать повторно без долгого выбора',
                'body' => 'Если нужен спокойный повторяемый заказ на неделю, начните с утренней подборки: короткий состав, понятное происхождение и стабильные позиции.',
                'cta_label' => 'Собрать утренний заказ',
                'image' => '/images/categories/dairy.jpg',
                'theme' => 'sage',
                'placement' => 'home',
                'sort_order' => 20,
                'is_published' => true,
                'collection_slug' => 'zavtrak-bez-marketpleysa',
            ],
            [
                'name' => 'Навигатор по молочной витрине',
                'eyebrow' => 'Промо в каталоге',
                'badge' => 'Начните с хитов',
                'title' => 'Если не знаете, с чего начать, откройте фермерскую молочку и базовые продукты на каждый день',
                'body' => 'Это самый понятный сценарий для первого заказа: молоко, творог, яйца, кефир и продукты, на которых лучше всего видно качество поставщиков и уровень сервиса.',
                'cta_label' => 'Перейти в молочную категорию',
                'image' => '/images/categories/dairy.jpg',
                'theme' => 'charcoal',
                'placement' => 'catalog',
                'sort_order' => 10,
                'is_published' => true,
                'category_slug' => 'dairy',
            ],
            [
                'name' => 'К ужину в один клик',
                'eyebrow' => 'Промо в каталоге',
                'badge' => 'Ужин без хаоса',
                'title' => 'Соберите ужин из одной подборки: мясо, овощи и зелень без долгого блуждания по каталогу',
                'body' => 'Отобрали набор для вечера, где позиции уже хорошо сочетаются друг с другом и не требуют длинной подготовки перед доставкой.',
                'cta_label' => 'Открыть подборку на вечер',
                'image' => '/images/categories/meat.jpg',
                'theme' => 'wheat',
                'placement' => 'catalog',
                'sort_order' => 20,
                'is_published' => true,
                'collection_slug' => 'gotovo-k-uzhinu',
            ],
            [
                'name' => 'Фермерский мед как подарочная позиция',
                'eyebrow' => 'Отдельный товар',
                'badge' => 'Подарок и кладовая',
                'title' => 'Натуральный мед с понятным сортом и регионом сбора — хорошая позиция для первого пробного заказа',
                'body' => 'Если хочется взять что-то не ежедневное, но показательное по качеству, начните с натурального меда из проверенной пасеки и продуктов с длинным shelf life.',
                'cta_label' => 'Открыть мед',
                'image' => '/images/categories/honey.jpg',
                'theme' => 'sage',
                'placement' => 'home',
                'sort_order' => 30,
                'is_published' => true,
                'product_slug' => 'med-cvetochnyy-naturalnyy',
            ],
        ];

        foreach ($promos as $promo) {
            $promo['category_id'] = filled($promo['category_slug'] ?? null) ? $categoryIds->get($promo['category_slug']) : null;
            $promo['collection_id'] = filled($promo['collection_slug'] ?? null) ? $collectionIds->get($promo['collection_slug']) : null;
            $promo['product_id'] = filled($promo['product_slug'] ?? null) ? $productIds->get($promo['product_slug']) : null;

            unset($promo['category_slug'], $promo['collection_slug'], $promo['product_slug']);

            PromoBlock::query()->updateOrCreate(
                ['name' => $promo['name']],
                $promo
            );
        }
    }
}
