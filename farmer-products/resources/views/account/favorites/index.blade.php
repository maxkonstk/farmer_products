@extends('layouts.site')

@section('title', 'Избранное')

@section('content')
    <section class="page-section">
        <div class="site-container">
            @include('account._nav')

            <div class="page-intro">
                <div>
                    <p class="eyebrow">Личный кабинет</p>
                    <h1 class="page-title">Избранные продукты</h1>
                    <p class="page-subtitle">Сохраненные позиции для повторных и сезонных заказов.</p>
                </div>
            </div>

            @if ($products->isEmpty())
                <div class="empty-state">
                    <h2>Пока ничего не сохранено</h2>
                    <p>Добавляйте продукты в избранное из каталога и карточек товара.</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary">Перейти в каталог</a>
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
