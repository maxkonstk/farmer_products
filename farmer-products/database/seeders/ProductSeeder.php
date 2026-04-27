<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['category' => 'vegetables', 'name' => 'Помидоры тепличные', 'slug' => 'pomidory-teplichnye', 'price' => 220, 'weight' => '1 кг', 'stock' => 45, 'featured' => true, 'image' => '/images/products/tomatoes.jpg', 'description' => 'Сочные красные помидоры с мягкой мякотью для салатов, закусок и домашней кухни.'],
            ['category' => 'vegetables', 'name' => 'Огурцы свежие', 'slug' => 'ogurcy-svezhie', 'price' => 190, 'weight' => '1 кг', 'stock' => 38, 'featured' => false, 'image' => '/images/products/cucumbers.jpg', 'description' => 'Хрустящие огурцы без горечи, выращенные в фермерской теплице без агрессивной химии.'],
            ['category' => 'vegetables', 'name' => 'Морковь молодая', 'slug' => 'morkov-molodaya', 'price' => 110, 'weight' => '1 кг', 'stock' => 52, 'featured' => false, 'image' => '/images/products/carrots.jpg', 'description' => 'Молодая сладкая морковь для салатов, супов и полезных перекусов.'],
            ['category' => 'vegetables', 'name' => 'Картофель деревенский', 'slug' => 'kartofel-derevenskiy', 'price' => 95, 'weight' => '1 кг', 'stock' => 60, 'featured' => false, 'image' => '/images/categories/vegetables.jpg', 'description' => 'Рассыпчатый картофель классического вкуса, подходящий для жарки, варки и запекания.'],
            ['category' => 'vegetables', 'name' => 'Свекла сладкая фермерская', 'slug' => 'svekla-sladkaya-fermerskaya', 'price' => 125, 'weight' => '1 кг', 'stock' => 33, 'featured' => false, 'image' => '/images/products/carrots.jpg', 'description' => 'Плотная сладкая свекла для борща, салатов и домашних овощных гарниров.'],
            ['category' => 'vegetables', 'name' => 'Кабачки фермерские', 'slug' => 'kabachki-fermerskie', 'price' => 165, 'weight' => '1 кг', 'stock' => 27, 'featured' => true, 'image' => '/images/products/cucumbers.jpg', 'description' => 'Нежные кабачки для рагу, запеканок и летних овощных блюд.'],

            ['category' => 'fruits', 'name' => 'Яблоки сезонные', 'slug' => 'yabloki-sezonnye', 'price' => 180, 'weight' => '1 кг', 'stock' => 40, 'featured' => true, 'image' => '/images/products/apples.jpg', 'description' => 'Фермерские яблоки с насыщенным вкусом и натуральной сладостью без восковой обработки.'],
            ['category' => 'fruits', 'name' => 'Груши садовые', 'slug' => 'grushi-sadovye', 'price' => 260, 'weight' => '1 кг', 'stock' => 24, 'featured' => false, 'image' => '/images/products/pears.jpg', 'description' => 'Сочные груши средней мягкости для свежего употребления и домашних десертов.'],
            ['category' => 'fruits', 'name' => 'Слива домашняя', 'slug' => 'sliva-domashnyaya', 'price' => 250, 'weight' => '1 кг', 'stock' => 21, 'featured' => false, 'image' => '/images/products/plums.jpg', 'description' => 'Спелая слива для компотов, выпечки и повседневных фруктовых тарелок.'],
            ['category' => 'fruits', 'name' => 'Абрикосы южные фермерские', 'slug' => 'abrikosy-yuzhnye-fermerskie', 'price' => 340, 'weight' => '1 кг', 'stock' => 18, 'featured' => false, 'image' => '/images/categories/fruits.jpg', 'description' => 'Сладкие абрикосы с бархатистой кожицей для десертов, варенья и летних перекусов.'],
            ['category' => 'fruits', 'name' => 'Персики сезонные', 'slug' => 'persiki-sezonnye', 'price' => 360, 'weight' => '1 кг', 'stock' => 16, 'featured' => false, 'image' => '/images/categories/fruits.jpg', 'description' => 'Ароматные персики с сочной мякотью для домашней кухни и фруктовых тарелок.'],
            ['category' => 'fruits', 'name' => 'Виноград столовый', 'slug' => 'vinograd-stolovyy', 'price' => 310, 'weight' => '800 г', 'stock' => 19, 'featured' => true, 'image' => '/images/categories/fruits.jpg', 'description' => 'Свежий сладкий виноград без лишней обработки для десертов и перекусов.'],

            ['category' => 'berries', 'name' => 'Клубника фермерская', 'slug' => 'klubnika-fermerskaya', 'price' => 420, 'weight' => '500 г', 'stock' => 18, 'featured' => true, 'image' => '/images/products/strawberries.jpg', 'description' => 'Ароматная клубника, собранная вручную и доставленная в день фасовки.'],
            ['category' => 'berries', 'name' => 'Малина свежая', 'slug' => 'malina-svezhaya', 'price' => 460, 'weight' => '300 г', 'stock' => 14, 'featured' => false, 'image' => '/images/products/raspberries.jpg', 'description' => 'Нежная малина для десертов, каш и летних витаминных перекусов.'],
            ['category' => 'berries', 'name' => 'Черника лесная', 'slug' => 'chernika-lesnaya', 'price' => 520, 'weight' => '300 г', 'stock' => 11, 'featured' => true, 'image' => '/images/products/blueberries.jpg', 'description' => 'Темная лесная черника с ярким вкусом для выпечки, морсов и завтраков.'],
            ['category' => 'berries', 'name' => 'Смородина красная', 'slug' => 'smorodina-krasnaya', 'price' => 290, 'weight' => '400 г', 'stock' => 15, 'featured' => false, 'image' => '/images/categories/berries.jpg', 'description' => 'Красная смородина с освежающей кислинкой для морсов, желе и заготовок.'],
            ['category' => 'berries', 'name' => 'Ежевика садовая', 'slug' => 'ezhevika-sadovaya', 'price' => 410, 'weight' => '300 г', 'stock' => 12, 'featured' => false, 'image' => '/images/categories/berries.jpg', 'description' => 'Спелая ежевика для домашних десертов и ягодных миксов.'],

            ['category' => 'greens', 'name' => 'Салат романо фермерский', 'slug' => 'salat-romano-fermerskiy', 'price' => 160, 'weight' => '1 шт.', 'stock' => 20, 'featured' => false, 'image' => '/images/products/lettuce.jpg', 'description' => 'Хрустящий романо для салатов, закусок и легких летних блюд.'],
            ['category' => 'greens', 'name' => 'Укроп свежий', 'slug' => 'ukrop-svezhiy', 'price' => 65, 'weight' => '100 г', 'stock' => 40, 'featured' => false, 'image' => '/images/products/herbs.jpg', 'description' => 'Ароматный укроп для супов, салатов и домашних солений.'],
            ['category' => 'greens', 'name' => 'Петрушка пучок', 'slug' => 'petrushka-puchok', 'price' => 70, 'weight' => '100 г', 'stock' => 36, 'featured' => false, 'image' => '/images/products/herbs.jpg', 'description' => 'Свежая петрушка для повседневной кухни, соусов и бульонов.'],
            ['category' => 'greens', 'name' => 'Базилик зеленый', 'slug' => 'bazilik-zelenyy', 'price' => 95, 'weight' => '70 г', 'stock' => 18, 'featured' => true, 'image' => '/images/products/herbs.jpg', 'description' => 'Пряный базилик для салатов, пасты и домашних соусов.'],
            ['category' => 'greens', 'name' => 'Лук зеленый фермерский', 'slug' => 'luk-zelenyy-fermerskiy', 'price' => 85, 'weight' => '120 г', 'stock' => 28, 'featured' => false, 'image' => '/images/categories/greens.jpg', 'description' => 'Молодой зеленый лук для окрошки, салатов и летних блюд.'],

            ['category' => 'dairy', 'name' => 'Молоко фермерское 1 л', 'slug' => 'moloko-fermerskoe-1l', 'price' => 130, 'weight' => '1 л', 'stock' => 35, 'featured' => true, 'image' => '/images/products/milk.jpg', 'description' => 'Свежее цельное молоко с мягким сливочным вкусом от небольшого семейного хозяйства.'],
            ['category' => 'dairy', 'name' => 'Сметана домашняя', 'slug' => 'smetana-domashnyaya', 'price' => 250, 'weight' => '300 г', 'stock' => 20, 'featured' => false, 'image' => '/images/categories/dairy.jpg', 'description' => 'Густая домашняя сметана для супов, выпечки и традиционных блюд.'],
            ['category' => 'dairy', 'name' => 'Творог фермерский', 'slug' => 'tvorog-fermerskiy', 'price' => 320, 'weight' => '500 г', 'stock' => 22, 'featured' => true, 'image' => '/images/products/cottage-cheese.jpg', 'description' => 'Нежный рассыпчатый творог без добавок, приготовленный из натурального молока.'],
            ['category' => 'dairy', 'name' => 'Яйца куриные деревенские', 'slug' => 'yayca-kurinye-derevenskie', 'price' => 180, 'weight' => '10 шт.', 'stock' => 40, 'featured' => false, 'image' => '/images/categories/dairy.jpg', 'description' => 'Деревенские яйца от кур свободного выгула с ярким желтком.'],
            ['category' => 'dairy', 'name' => 'Кефир фермерский 1 л', 'slug' => 'kefir-fermerskiy-1l', 'price' => 145, 'weight' => '1 л', 'stock' => 24, 'featured' => false, 'image' => '/images/products/milk.jpg', 'description' => 'Натуральный кефир без лишних добавок для завтраков и легких ужинов.'],
            ['category' => 'dairy', 'name' => 'Ряженка домашняя', 'slug' => 'ryazhenka-domashnyaya', 'price' => 165, 'weight' => '500 мл', 'stock' => 19, 'featured' => false, 'image' => '/images/products/milk.jpg', 'description' => 'Нежная ряженка с мягким топленым вкусом и натуральным составом.'],

            ['category' => 'cheese', 'name' => 'Сыр адыгейский фермерский', 'slug' => 'syr-adygeyskiy-fermerskiy', 'price' => 360, 'weight' => '400 г', 'stock' => 17, 'featured' => true, 'image' => '/images/products/cheese-board.jpg', 'description' => 'Свежий мягкий сыр для салатов, закусок и запекания.'],
            ['category' => 'cheese', 'name' => 'Сыр козий мягкий', 'slug' => 'syr-koziy-myagkiy', 'price' => 490, 'weight' => '250 г', 'stock' => 12, 'featured' => false, 'image' => '/images/products/cheese-board.jpg', 'description' => 'Нежный козий сыр с выразительным вкусом для гастрономичных закусок.'],
            ['category' => 'cheese', 'name' => 'Масло сливочное 82,5%', 'slug' => 'maslo-slivochnoe-82-5', 'price' => 260, 'weight' => '180 г', 'stock' => 26, 'featured' => false, 'image' => '/images/products/butter.jpg', 'description' => 'Натуральное сливочное масло с плотной текстурой и ярким молочным вкусом.'],
            ['category' => 'cheese', 'name' => 'Брынза домашняя', 'slug' => 'brynza-domashnyaya', 'price' => 410, 'weight' => '350 г', 'stock' => 16, 'featured' => false, 'image' => '/images/categories/cheese.jpg', 'description' => 'Соленая домашняя брынза для салатов, закусок и выпечки.'],
            ['category' => 'cheese', 'name' => 'Сыр качотта фермерский', 'slug' => 'syr-kachotta-fermerskiy', 'price' => 520, 'weight' => '300 г', 'stock' => 11, 'featured' => true, 'image' => '/images/categories/cheese.jpg', 'description' => 'Полутвердый фермерский сыр с мягким сливочным ароматом.'],

            ['category' => 'meat', 'name' => 'Куриное филе охлажденное', 'slug' => 'kurinoe-file-ohlazhdennoe', 'price' => 410, 'weight' => '1 кг', 'stock' => 16, 'featured' => true, 'image' => '/images/products/chicken.jpg', 'description' => 'Свежайшее филе курицы без заморозки для полезных домашних блюд.'],
            ['category' => 'meat', 'name' => 'Фарш домашний говяжий', 'slug' => 'farsh-domashniy-govyazhiy', 'price' => 560, 'weight' => '1 кг', 'stock' => 14, 'featured' => false, 'image' => '/images/products/beef.jpg', 'description' => 'Фарш из охлажденной говядины без лишних добавок и усилителей вкуса.'],
            ['category' => 'meat', 'name' => 'Бедро индейки фермерское', 'slug' => 'bedro-indeyki-fermerskoe', 'price' => 480, 'weight' => '1 кг', 'stock' => 12, 'featured' => false, 'image' => '/images/categories/meat.jpg', 'description' => 'Мясо индейки для запекания и тушения с насыщенным натуральным вкусом.'],
            ['category' => 'meat', 'name' => 'Куриные бедра домашние', 'slug' => 'kurinye-bedra-domashnie', 'price' => 330, 'weight' => '1 кг', 'stock' => 18, 'featured' => false, 'image' => '/images/products/chicken.jpg', 'description' => 'Охлажденные куриные бедра для запекания, жарки и домашних бульонов.'],
            ['category' => 'meat', 'name' => 'Колбаски фермерские для жарки', 'slug' => 'kolbaski-fermerskie-dlya-zharki', 'price' => 540, 'weight' => '700 г', 'stock' => 10, 'featured' => true, 'image' => '/images/categories/meat.jpg', 'description' => 'Домашние колбаски из охлажденного мяса для гриля и сковороды.'],
            ['category' => 'meat', 'name' => 'Свинина шейка охлажденная', 'slug' => 'svinina-sheyka-ohlazhdennaya', 'price' => 460, 'weight' => '1 кг', 'stock' => 13, 'featured' => false, 'image' => '/images/products/beef.jpg', 'description' => 'Сочная охлажденная шейка для шашлыка, запекания и тушения.'],

            ['category' => 'honey', 'name' => 'Мед цветочный натуральный', 'slug' => 'med-cvetochnyy-naturalnyy', 'price' => 390, 'weight' => '500 г', 'stock' => 28, 'featured' => true, 'image' => '/images/products/honey-jar.jpg', 'description' => 'Светлый цветочный мед с мягким ароматом луговых трав и приятной сладостью.'],
            ['category' => 'honey', 'name' => 'Мед гречишный', 'slug' => 'med-grechishnyy', 'price' => 430, 'weight' => '500 г', 'stock' => 17, 'featured' => false, 'image' => '/images/products/honeycomb.jpg', 'description' => 'Темный мед с ярким вкусом и выраженным ароматом, богатый микроэлементами.'],
            ['category' => 'honey', 'name' => 'Пыльца пчелиная', 'slug' => 'pylca-pchelinaya', 'price' => 280, 'weight' => '200 г', 'stock' => 15, 'featured' => false, 'image' => '/images/products/honeycomb.jpg', 'description' => 'Натуральный продукт пчеловодства для ежедневного рациона и полезных смесей.'],
            ['category' => 'honey', 'name' => 'Мед липовый натуральный', 'slug' => 'med-lipovyy-naturalnyy', 'price' => 410, 'weight' => '500 г', 'stock' => 18, 'featured' => true, 'image' => '/images/products/honey-jar.jpg', 'description' => 'Липовый мед с тонким ароматом и мягким послевкусием для чая и десертов.'],
            ['category' => 'honey', 'name' => 'Перга пчелиная', 'slug' => 'perga-pchelinaya', 'price' => 340, 'weight' => '180 г', 'stock' => 9, 'featured' => false, 'image' => '/images/products/honeycomb.jpg', 'description' => 'Перга из пасеки для натурального ежедневного рациона и фермерских наборов.'],

            ['category' => 'bakery', 'name' => 'Хлеб домашний цельнозерновой', 'slug' => 'hleb-domashniy-celnozernovoy', 'price' => 150, 'weight' => '650 г', 'stock' => 26, 'featured' => true, 'image' => '/images/products/bread.jpg', 'description' => 'Плотный ароматный хлеб на закваске с цельнозерновой мукой и хрустящей корочкой.'],
            ['category' => 'bakery', 'name' => 'Булочки с творогом', 'slug' => 'bulochki-s-tvorogom', 'price' => 190, 'weight' => '4 шт.', 'stock' => 14, 'featured' => false, 'image' => '/images/products/pastry.jpg', 'description' => 'Нежные домашние булочки со сладкой творожной начинкой к чаю или завтраку.'],
            ['category' => 'bakery', 'name' => 'Пирог яблочный деревенский', 'slug' => 'pirog-yablochnyy-derevenskiy', 'price' => 320, 'weight' => '800 г', 'stock' => 10, 'featured' => false, 'image' => '/images/products/pastry.jpg', 'description' => 'Домашний яблочный пирог с большим количеством начинки и тонким тестом.'],
            ['category' => 'bakery', 'name' => 'Багет на закваске фермерский', 'slug' => 'baget-na-zakvaske-fermerskiy', 'price' => 170, 'weight' => '350 г', 'stock' => 20, 'featured' => false, 'image' => '/images/products/bread.jpg', 'description' => 'Небольшой багет на закваске для завтраков, сэндвичей и домашних закусок.'],
            ['category' => 'bakery', 'name' => 'Печенье овсяное домашнее', 'slug' => 'pechene-ovsyanoe-domashnee', 'price' => 210, 'weight' => '300 г', 'stock' => 22, 'featured' => false, 'image' => '/images/products/pastry.jpg', 'description' => 'Домашнее овсяное печенье с мягкой текстурой и насыщенным сливочным ароматом.'],
            ['category' => 'bakery', 'name' => 'Круассаны сливочные', 'slug' => 'kruassany-slivochnye', 'price' => 240, 'weight' => '3 шт.', 'stock' => 12, 'featured' => true, 'image' => '/images/products/pastry.jpg', 'description' => 'Слоеные круассаны на сливочном масле для утренней витрины и свежего кофе.'],

            ['category' => 'preserves', 'name' => 'Варенье клубничное домашнее', 'slug' => 'varene-klubnichnoe-domashnee', 'price' => 290, 'weight' => '350 г', 'stock' => 18, 'featured' => true, 'image' => '/images/products/jam.jpg', 'description' => 'Клубничное варенье с крупными ягодами и насыщенным летним вкусом.'],
            ['category' => 'preserves', 'name' => 'Варенье малиновое натуральное', 'slug' => 'varene-malinovoe-naturalnoe', 'price' => 310, 'weight' => '350 г', 'stock' => 15, 'featured' => false, 'image' => '/images/products/jam.jpg', 'description' => 'Малиновое варенье без лишних добавок для чая, десертов и завтраков.'],
            ['category' => 'preserves', 'name' => 'Огурцы маринованные фермерские', 'slug' => 'ogurcy-marinovannye-fermerskie', 'price' => 240, 'weight' => '700 г', 'stock' => 21, 'featured' => false, 'image' => '/images/products/pickles.jpg', 'description' => 'Хрустящие маринованные огурцы по домашнему рецепту с зеленью и чесноком.'],
            ['category' => 'preserves', 'name' => 'Лечо домашнее овощное', 'slug' => 'lecho-domashnee-ovoshchnoe', 'price' => 280, 'weight' => '500 г', 'stock' => 17, 'featured' => false, 'image' => '/images/products/pickles.jpg', 'description' => 'Домашнее лечо из перца и томатов для гарниров и семейных ужинов.'],
            ['category' => 'preserves', 'name' => 'Повидло яблочное деревенское', 'slug' => 'povidlo-yablochnoe-derevenskoe', 'price' => 230, 'weight' => '350 г', 'stock' => 16, 'featured' => false, 'image' => '/images/products/jam.jpg', 'description' => 'Густое яблочное повидло для выпечки, тостов и домашних десертов.'],
        ];

        foreach ($products as $product) {
            $category = Category::query()->firstWhere('slug', $product['category']);

            if (! $category) {
                continue;
            }

            $resolvedImage = $product['image'];

            if (str_starts_with($resolvedImage, '/') && ! file_exists(public_path(ltrim($resolvedImage, '/')))) {
                $resolvedImage = $category->image ?: '/images/products/fallback.svg';
            }

            Product::query()->updateOrCreate(
                ['slug' => $product['slug']],
                [
                    'category_id' => $category->id,
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'image' => $resolvedImage,
                    'weight' => $product['weight'],
                    'stock' => $product['stock'],
                    'is_active' => true,
                    'is_featured' => $product['featured'],
                ]
            );
        }
    }
}
