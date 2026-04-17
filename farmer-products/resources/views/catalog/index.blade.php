@extends('layouts.site')

@section('title', $currentCategory ? $currentCategory->name : 'Каталог товаров')

@section('content')
    <section class="page-section">
        <div class="site-container">
            <div class="page-intro">
                <div>
                    <p class="eyebrow">{{ $currentCategory ? 'Рубрика номера' : 'Архив журнала' }}</p>
                    <h1 class="page-title">{{ $currentCategory?->name ?? 'Фермерские продукты' }}</h1>
                    <p class="page-subtitle">{{ $currentCategory?->description ?? 'Ищите продукты как материалы журнала: по теме, названию и цене, не теряя редакционного ощущения витрины.' }}</p>
                </div>
                <div class="intro-note">
                    <span>В номере</span>
                    <strong>{{ $products->total() }} материалов</strong>
                </div>
            </div>

            <form method="GET" class="filter-panel">
                <div class="form-group">
                    <label for="q" class="form-label">Поиск по материалам</label>
                    <input id="q" type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Например, молоко, мед, хлеб">
                </div>
                <div class="form-group">
                    <label for="sort" class="form-label">Редакционный порядок</label>
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
                    <h2>Материалы не найдены</h2>
                    <p>Попробуйте изменить параметры поиска или перейти в другую рубрику журнала.</p>
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
