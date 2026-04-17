@extends('layouts.site')

@section('title', 'Главная')

@section('content')
    @php
        $productCount = $categories->sum('products_count');
        $issueTheme = $categories->first()?->name ?? 'Сезонные продукты';
    @endphp

    <section class="hero-section">
        <div class="site-container hero-grid">
            <div class="hero-copy">
                <p class="eyebrow">Журнал фермерских продуктов</p>
                <h1 class="hero-title">Свежие фермерские продукты на каждый день</h1>
                <p class="hero-text">Компактная витрина с сезонными категориями, понятным каталогом и быстрым переходом к покупке.</p>
                <div class="hero-actions">
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-large">Открыть каталог</a>
                    <a href="{{ route('pages.about') }}" class="btn btn-outline btn-large">О проекте</a>
                </div>

                <div class="editorial-strip">
                    <div class="editorial-strip__item">
                        <span>Тема выпуска</span>
                        <strong>{{ $issueTheme }}</strong>
                    </div>
                    <div class="editorial-strip__item">
                        <span>Материалы</span>
                        <strong>{{ $productCount }} продуктов</strong>
                    </div>
                    <div class="editorial-strip__item">
                        <span>Фокус</span>
                        <strong>Происхождение и сезонность</strong>
                    </div>
                </div>
            </div>

            <div class="hero-card hero-card--issue">
                <img src="/images/hero/farm-market.jpg" alt="Фермерские продукты" class="hero-card__image">
                <div class="hero-card__content">
                    <p class="hero-card__label">Номер сезона</p>
                    <h2 class="hero-card__headline">Сезонный набор продуктов без перегруженной витрины</h2>
                    <p class="hero-card__excerpt">Все ключевые категории собраны в одном плотном первом экране.</p>
                    <div class="hero-card__facts">
                        <div class="hero-card__item">
                            <span class="hero-card__label">Категорий</span>
                            <strong>{{ $categories->count() }}</strong>
                        </div>
                        <div class="hero-card__item">
                            <span class="hero-card__label">Подборка</span>
                            <strong>{{ $featuredProducts->count() }} материалов</strong>
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
                    <p class="eyebrow">Рубрики номера</p>
                    <h2 class="section-title">Каталог как набор редакционных разделов</h2>
                </div>
                <a href="{{ route('catalog.index') }}" class="section-link">Смотреть все рубрики</a>
            </div>

            <div class="category-grid">
                @foreach ($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="category-card">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="category-card__image">
                        <div class="category-card__content">
                            <span class="category-card__kicker">Раздел</span>
                            <h3>{{ $category->name }}</h3>
                            <p>{{ $category->description }}</p>
                            <span>{{ $category->products_count }} материалов в подборке</span>
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
                    <p class="eyebrow">Главные материалы</p>
                    <h2 class="section-title">Редакционная подборка этой недели</h2>
                </div>
                <p class="section-note">Карточки читаются как журнальные заметки, но по-прежнему ведут к покупке.</p>
            </div>

            <div class="product-grid">
                @foreach ($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="site-container benefit-grid">
            <article class="benefit-card">
                <h3>Сезон как сюжет</h3>
                <p>Каждый раздел каталога подается как тема номера: что сейчас в сезоне, что стоит попробовать и что удобно купить сразу.</p>
            </article>
            <article class="benefit-card">
                <h3>Товар как заметка</h3>
                <p>Карточка продукта выглядит как короткий материал: заголовок, лид, происхождение и затем уже цена и действие.</p>
            </article>
            <article class="benefit-card">
                <h3>Журнал, который можно купить</h3>
                <p>Концепция editorial не мешает магазину: каталог, корзина, заказ и админ-панель остаются рабочими и логичными.</p>
            </article>
        </div>
    </section>
@endsection
