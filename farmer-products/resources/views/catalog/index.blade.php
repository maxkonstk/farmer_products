@extends('layouts.site')

@section('title', $currentCategory ? $currentCategory->name : 'Каталог товаров')
@section('meta_description', $currentCategory?->description ?? 'Каталог локальных фермерских продуктов: овощи, молочка, сыры, мясо, мед, выпечка и сезонные заготовки с доставкой по Самаре.')

@section('content')
    <section class="page-section">
        <div class="site-container">
            <div class="page-intro">
                <div>
                    <p class="eyebrow">{{ $currentCategory ? 'Категория каталога' : 'Весь ассортимент' }}</p>
                    <h1 class="page-title">{{ $currentCategory?->name ?? 'Фермерские продукты' }}</h1>
                    <p class="page-subtitle">{{ $currentCategory?->description ?? 'Ищите продукты по категории, сезонности, цене и наличию. Для каждой карточки показываем происхождение, базовые характеристики и понятный путь к покупке.' }}</p>
                </div>
                <div class="intro-note">
                    <span>В наличии на витрине</span>
                    <strong>{{ $products->total() }} товаров</strong>
                </div>
            </div>

            <form method="GET" class="filter-panel">
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

                <div class="form-group">
                    <label for="q" class="form-label">Поиск по товарам</label>
                    <input id="q" type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Например, молоко, мед, хлеб">
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
                    <button type="submit" class="btn btn-primary">Применить</button>
                    <a href="{{ $currentCategory ? route('categories.show', $currentCategory) : route('catalog.index') }}" class="btn btn-outline">Сбросить</a>
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
