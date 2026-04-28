@extends('layouts.site')

@section('title', 'Локальные фермерские продукты с доставкой по Самаре')
@section('meta_description', 'Локальные овощи, молочка, сыры, мясо, мед и домашняя выпечка с понятным происхождением, ручным подтверждением заказа и доставкой по Самаре.')

@push('head')
    <link rel="preload" as="image" href="{{ asset('images/products/hero-farm.svg') }}" fetchpriority="high">
@endpush

@push('structured_data')
    @php
        $searchUrl = route('catalog.index').'?q={search_term_string}';
    @endphp
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => route('home').'#organization',
                    'name' => $shopBrand['name'],
                    'url' => route('home'),
                    'telephone' => $shopBrand['phone'],
                    'email' => $shopBrand['email'],
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => $shopBrand['city'],
                        'streetAddress' => $shopBrand['address'],
                    ],
                ],
                [
                    '@type' => 'Store',
                    '@id' => route('home').'#store',
                    'name' => $shopBrand['name'],
                    'image' => asset('images/products/hero-farm.svg'),
                    'url' => route('home'),
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => $shopBrand['city'],
                        'streetAddress' => $shopBrand['address'],
                    ],
                    'parentOrganization' => [
                        '@id' => route('home').'#organization',
                    ],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => route('home').'#website',
                    'name' => $shopBrand['name'],
                    'url' => route('home'),
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => $searchUrl,
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    @php
        $productCount = $categories->sum('products_count');
        $brand = $shopBrand;
    @endphp

    <section class="hero-section hero-section--commerce">
        <div class="site-container hero-grid hero-grid--commerce">
            <div class="hero-copy">
                <p class="eyebrow">Локальный food-commerce бренд</p>
                <h1 class="hero-title">Фермерские продукты с понятным происхождением и спокойной доставкой по Самаре</h1>
                <p class="hero-text">{{ $brand['hero_note'] }}</p>
                <div class="hero-actions">
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-large">Собрать корзину</a>
                    <a href="{{ route('pages.delivery') }}" class="btn btn-outline btn-large">Как работает доставка</a>
                </div>

                <div class="hero-kpis">
                    <div class="hero-kpi">
                        <span>Ассортимент</span>
                        <strong>{{ $productCount }} товаров</strong>
                    </div>
                    <div class="hero-kpi">
                        <span>Поставщики</span>
                        <strong>{{ count($farmers) }} проверенных хозяйства</strong>
                    </div>
                    <div class="hero-kpi">
                        <span>Формат</span>
                        <strong>Доставка + самовывоз</strong>
                    </div>
                </div>
            </div>

            <div class="hero-card hero-card--issue">
                <img src="/images/products/hero-farm.svg" alt="Сезонные поставки фермерских продуктов" class="hero-card__image" fetchpriority="high">
                <div class="hero-card__content">
                    <p class="hero-card__label">Почему это работает</p>
                    <h2 class="hero-card__headline">Не маркетплейс, а короткая цепочка от хозяйства до вашей кухни</h2>
                    <p class="hero-card__excerpt">Подтверждаем заказ вручную, предупреждаем о заменах заранее и собираем корзину небольшими партиями под конкретный слот доставки.</p>
                    <div class="hero-card__facts">
                        <div class="hero-card__item">
                            <span class="hero-card__label">География</span>
                            <strong>{{ $brand['city'] }}</strong>
                        </div>
                        <div class="hero-card__item">
                            <span class="hero-card__label">Категорий</span>
                            <strong>{{ $categories->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Как это устроено</p>
                    <h2 class="section-title">Три шага от витрины до двери</h2>
                </div>
            </div>

            <div class="steps-grid">
                <article class="step-card">
                    <span class="step-card__number">01</span>
                    <h3>Выбираете сезонные продукты</h3>
                    <p>В каталоге сразу видно категорию, цену, наличие и контекст: от какой фермы товар и как его лучше хранить.</p>
                </article>
                <article class="step-card">
                    <span class="step-card__number">02</span>
                    <h3>Подтверждаем состав заказа</h3>
                    <p>Если остаток изменился, сначала связываемся с вами. Никаких неожиданных замен и скрытых списаний.</p>
                </article>
                <article class="step-card">
                    <span class="step-card__number">03</span>
                    <h3>Привозим в выбранное окно</h3>
                    <p>Молочку и мясо везем в холодовой цепочке, выпечку и зелень — в ближайшем доступном слоте после сборки.</p>
                </article>
            </div>
        </div>
    </section>

    @if ($featuredCollections->isNotEmpty())
        <section class="section section--muted">
            <div class="site-container">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow">Подборки и коллекции</p>
                        <h2 class="section-title">Готовые сценарии покупки вместо хаотичного каталога</h2>
                    </div>
                    <p class="section-note">Собираем тематические наборы под сезон, повседневный заказ и повторные покупки.</p>
                </div>

                <div class="collection-grid">
                    @foreach ($featuredCollections as $collection)
                        <a href="{{ route('collections.show', $collection) }}" class="collection-card">
                            <div class="collection-card__media">
                                <img src="{{ $collection->image_url }}" alt="{{ $collection->name }}" loading="lazy" decoding="async">
                                @if ($collection->badge)
                                    <span class="product-badge">{{ $collection->badge }}</span>
                                @endif
                            </div>
                            <div class="collection-card__content">
                                <p class="eyebrow">Подборка</p>
                                <h3>{{ $collection->name }}</h3>
                                <p>{{ $collection->intro ?: $collection->description }}</p>
                                <span>{{ $collection->products_count }} товаров в подборке</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Категории</p>
                    <h2 class="section-title">Ассортимент для ежедневной и сезонной корзины</h2>
                </div>
                <a href="{{ route('catalog.index') }}" class="section-link">Открыть весь каталог</a>
            </div>

            <div class="category-grid">
                @foreach ($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="category-card">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="category-card__image" loading="lazy" decoding="async">
                        <div class="category-card__content">
                            <span class="category-card__kicker">Категория</span>
                            <h3>{{ $category->name }}</h3>
                            <p>{{ $category->description }}</p>
                            <span>{{ $category->products_count }} товаров в подборке</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--muted">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Хиты недели</p>
                    <h2 class="section-title">Товары, с которых обычно начинают заказ</h2>
                </div>
                <p class="section-note">Отобрали позиции, которые лучше всего объясняют формат магазина: понятный состав, короткий путь поставки и стабильный спрос.</p>
            </div>

            <div class="product-grid">
                @foreach ($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Фермеры и хозяйства</p>
                    <h2 class="section-title">С кем мы работаем</h2>
                </div>
            </div>

            <div class="farm-grid">
                @foreach ($farmers as $farmer)
                    <article class="farm-card">
                        <img src="{{ $farmer['image_url'] ?? $farmer['image'] }}" alt="{{ $farmer['name'] }}" class="farm-card__image" loading="lazy" decoding="async">
                        <div>
                            <p class="farm-card__eyebrow">{{ $farmer['location'] }}</p>
                            <h3>{{ $farmer['name'] }}</h3>
                            <p class="farm-card__specialty">{{ $farmer['specialty'] }}</p>
                            <p>{{ $farmer['story'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--muted">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Почему нам доверяют</p>
                    <h2 class="section-title">Прозрачный сервис без маркетплейсных компромиссов</h2>
                </div>
            </div>

            <div class="trust-strip">
                @foreach ($promises as $promise)
                    <article class="trust-card">
                        <h3>{{ $loop->iteration < 10 ? '0'.$loop->iteration : $loop->iteration }}</h3>
                        <p>{{ $promise }}</p>
                    </article>
                @endforeach
            </div>

            <div class="delivery-zone-grid">
                @foreach ($deliveryZones as $zone)
                    <article class="delivery-zone-card">
                        <span>Зона обслуживания</span>
                        <strong>{{ $zone }}</strong>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Отзывы</p>
                    <h2 class="section-title">Что важно для постоянных покупателей</h2>
                </div>
            </div>

            <div class="testimonial-grid">
                @foreach ($testimonials as $testimonial)
                    <blockquote class="testimonial-card">
                        <p>“{{ $testimonial['quote'] }}”</p>
                        <footer>
                            {{ $testimonial['author'] }}
                            @if (! empty($testimonial['role']))
                                · {{ $testimonial['role'] }}
                            @endif
                        </footer>
                    </blockquote>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="site-container">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Частые вопросы</p>
                    <h2 class="section-title">Что обычно спрашивают перед первым заказом</h2>
                </div>
            </div>

            <div class="faq-list">
                @foreach ($faqItems as $faqItem)
                    <details class="faq-item" @if ($loop->first) open @endif>
                        <summary>{{ $faqItem['question'] }}</summary>
                        <div class="faq-answer">
                            <p>{{ $faqItem['answer'] }}</p>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--muted">
        <div class="site-container seo-copy">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Для тех, кто ищет локальные продукты</p>
                    <h2 class="section-title">Небольшой фермерский магазин, который можно использовать каждую неделю</h2>
                </div>
            </div>
            <p>«Фермерская лавка» — это не промо-лендинг и не безликий каталог. Мы собрали формат локального food-commerce сервиса для Самары: сезонные овощи, молочная продукция, сыры, мясо, мед, выпечка и домашние заготовки от небольших поставщиков. Покупателю не нужно гадать, что произойдет после оформления: мы подтверждаем заказ, согласуем замены и привозим продукты в согласованное окно доставки.</p>
        </div>
    </section>
@endsection
