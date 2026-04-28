@extends('layouts.site')

@section('title', $currentCollection?->name ?? ($currentCategory ? $currentCategory->name : 'Каталог товаров'))
@section('meta_description', $currentCollection?->description ?? ($currentCategory?->description ?? 'Каталог локальных фермерских продуктов: овощи, молочка, сыры, мясо, мед, выпечка и сезонные заготовки с доставкой по Самаре.'))

@php
    $analytics = app(\App\Services\AnalyticsService::class);
    $pageTitle = $currentCollection?->name ?? ($currentCategory?->name ?? 'Фермерские продукты');
    $pageDescription = $currentCollection?->description ?? ($currentCategory?->description ?? 'Ищите продукты по категории, сезонности, цене и наличию. Для каждой карточки показываем происхождение, базовые характеристики и понятный путь к покупке.');
    $listId = $currentCollection?->slug ?? ($currentCategory?->slug ?? 'catalog');
    $listName = $currentCollection?->name ?? ($currentCategory?->name ?? 'Весь каталог');
    $analyticsItems = $analytics->itemsFromProducts($products->getCollection(), $listId, $listName);
    $breadcrumbItems = [
        ['name' => 'Главная', 'url' => route('home')],
        ['name' => 'Каталог', 'url' => route('catalog.index')],
    ];

    if ($currentCategory) {
        $breadcrumbItems[] = ['name' => $currentCategory->name, 'url' => route('categories.show', $currentCategory)];
    }

    if ($currentCollection) {
        $breadcrumbItems[] = ['name' => $currentCollection->name, 'url' => route('collections.show', $currentCollection)];
    }

    $activeFilterCount = collect([
        $search !== '',
        ! $currentCategory && filled(request('category')),
        ! $currentCollection && filled(request('collection')),
        $selectedSeasonality !== '',
        $availability !== '',
        $sort !== 'latest',
    ])->filter()->count();
@endphp

@push('structured_data')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => collect($breadcrumbItems)
                        ->values()
                        ->map(fn (array $item, int $index) => [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'name' => $item['name'],
                            'item' => $item['url'],
                        ])
                        ->all(),
                ],
                [
                    '@type' => 'CollectionPage',
                    'name' => $pageTitle,
                    'description' => $pageDescription,
                    'url' => url()->full(),
                    'mainEntity' => [
                        '@type' => 'ItemList',
                        'numberOfItems' => $products->count(),
                        'itemListElement' => $products->getCollection()
                            ->values()
                            ->map(fn ($product, int $index) => [
                                '@type' => 'ListItem',
                                'position' => $index + 1,
                                'url' => route('products.show', $product),
                                'item' => [
                                    '@type' => 'Product',
                                    'name' => $product->name,
                                    'image' => $product->image_url,
                                    'category' => $product->category->name,
                                    'offers' => [
                                        '@type' => 'Offer',
                                        'priceCurrency' => 'RUB',
                                        'price' => (float) $product->price,
                                        'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                                        'url' => route('products.show', $product),
                                    ],
                                ],
                            ])
                            ->all(),
                    ],
                ],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@push('analytics_events')
    @include('partials.analytics-event', [
        'event' => [
            'event' => 'view_item_list',
            'ecommerce' => [
                'item_list_id' => $listId,
                'item_list_name' => $listName,
                'items' => $analyticsItems,
            ],
        ],
    ])
    @if ($search !== '')
        @include('partials.analytics-event', [
            'event' => [
                'event' => 'view_search_results',
                'search_term' => $search,
                'results_count' => $products->total(),
            ],
        ])
    @endif
@endpush

@section('content')
    <section class="page-section">
        <div class="site-container" x-data="{ filtersOpen: false }" @keydown.escape.window="filtersOpen = false">
            <div class="page-intro">
                <div>
                    <p class="eyebrow">{{ $currentCollection ? 'Коллекция' : ($currentCategory ? 'Категория каталога' : 'Весь ассортимент') }}</p>
                    <h1 class="page-title">{{ $pageTitle }}</h1>
                    <p class="page-subtitle">{{ $pageDescription }}</p>
                </div>
                <div class="intro-note">
                    <span>В наличии на витрине</span>
                    <strong>{{ $products->total() }} товаров</strong>
                </div>
            </div>

            @if ($catalogPromo)
                <div class="promo-inline-wrap">
                    @include('partials.promo-block', ['promoBlock' => $catalogPromo, 'variant' => 'inline'])
                </div>
            @endif

            <button
                type="button"
                class="filter-toggle"
                aria-controls="catalog-filters"
                :aria-expanded="filtersOpen.toString()"
                @click="filtersOpen = ! filtersOpen"
            >
                <span>{{ $activeFilterCount > 0 ? "Фильтры · {$activeFilterCount}" : 'Показать фильтры' }}</span>
                <span class="filter-toggle__hint">Поиск, сезонность, наличие</span>
            </button>

            <form method="GET" id="catalog-filters" class="filter-panel" :class="{ 'is-open': filtersOpen }">
                @if (! $currentCategory)
                    <div class="form-group">
                        <label for="category" class="form-label">Категория</label>
                        <select id="category" name="category" class="form-control">
                            <option value="">Все категории</option>
                            @foreach ($categories as $categoryOption)
                                <option value="{{ $categoryOption->slug }}" @selected(request('category') === $categoryOption->slug)>{{ $categoryOption->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if (! $currentCollection)
                    <div class="form-group">
                        <label for="collection" class="form-label">Подборка</label>
                        <select id="collection" name="collection" class="form-control">
                            <option value="">Все подборки</option>
                            @foreach ($collections as $collectionOption)
                                <option value="{{ $collectionOption->slug }}" @selected(request('collection') === $collectionOption->slug)>{{ $collectionOption->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-group">
                    <label for="q" class="form-label">Поиск по товарам</label>
                    <input id="q" type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Например, молоко, мед, хлеб" inputmode="search" enterkeyhint="search" spellcheck="false" autocapitalize="none">
                </div>
                <div class="form-group">
                    <label for="season" class="form-label">Сезонность</label>
                    <select id="season" name="season" class="form-control">
                        <option value="">Любая</option>
                        @foreach ($seasonalityOptions as $seasonalityOption)
                            <option value="{{ $seasonalityOption }}" @selected($selectedSeasonality === $seasonalityOption)>{{ $seasonalityOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="availability" class="form-label">Наличие</label>
                    <select id="availability" name="availability" class="form-control">
                        <option value="">Все позиции</option>
                        <option value="in-stock" @selected($availability === 'in-stock')>Только в наличии</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sort" class="form-label">Сортировка</label>
                    <select id="sort" name="sort" class="form-control">
                        <option value="latest" @selected($sort === 'latest')>Сначала новые</option>
                        <option value="name_asc" @selected($sort === 'name_asc')>По названию</option>
                        <option value="price_asc" @selected($sort === 'price_asc')>Сначала дешевле</option>
                        <option value="price_desc" @selected($sort === 'price_desc')>Сначала дороже</option>
                    </select>
                </div>
                <div class="filter-panel__actions">
                    <button type="button" class="btn btn-ghost filter-panel__dismiss" @click="filtersOpen = false">Скрыть фильтры</button>
                    <button type="submit" class="btn btn-primary">Применить</button>
                    <a href="{{ $currentCollection ? route('collections.show', $currentCollection) : ($currentCategory ? route('categories.show', $currentCategory) : route('catalog.index')) }}" class="btn btn-outline">Сбросить</a>
                </div>
            </form>

            @if ($products->isEmpty())
                <div class="empty-state">
                    <h2>Товары не найдены</h2>
                    <p>Попробуйте сбросить фильтры или перейти в другую категорию каталога.</p>
                </div>
            @else
                <div class="product-grid">
                    @foreach ($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                <div class="pagination-wrap">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
