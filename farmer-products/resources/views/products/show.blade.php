@extends('layouts.site')

@section('title', $product->name)

@section('content')
    <section class="page-section">
        <div class="site-container product-detail">
            <div class="product-detail__media">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-detail__image">
            </div>

            <div class="product-detail__content">
                <div class="product-story-meta">
                    <p class="eyebrow">{{ $product->category->name }}</p>
                    <span class="product-story-meta__tag">Материал журнала</span>
                </div>
                <h1 class="page-title">{{ $product->name }}</h1>
                <p class="page-subtitle">{{ $product->description }}</p>

                <div class="product-story-lead">
                    <span>О чем этот материал</span>
                    <p>Карточка раскрывает продукт как редакционную заметку: происхождение, объем, наличие и понятный переход к покупке без визуального шума.</p>
                </div>

                <div class="product-detail__facts">
                    <div class="detail-fact">
                        <span>Цена</span>
                        <strong>{{ number_format((float) $product->price, 0, ',', ' ') }} ₽</strong>
                    </div>
                    <div class="detail-fact">
                        <span>Вес / объем</span>
                        <strong>{{ $product->weight ?: 'Уточняется' }}</strong>
                    </div>
                    <div class="detail-fact">
                        <span>Остаток</span>
                        <strong>{{ $product->stock }} шт.</strong>
                    </div>
                </div>

                <div class="product-detail__actions">
                    @if ($product->stock > 0)
                        <form method="POST" action="{{ route('cart.store', $product) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-large">Добавить в корзину</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-disabled btn-large" disabled>Нет в наличии</button>
                    @endif
                    <a href="{{ route('categories.show', $product->category) }}" class="btn btn-outline btn-large">Все товары категории</a>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="section section--muted">
            <div class="site-container">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow">Еще в рубрике</p>
                        <h2 class="section-title">Продолжение темы «{{ $product->category->name }}»</h2>
                    </div>
                </div>

                <div class="product-grid">
                    @foreach ($relatedProducts as $relatedProduct)
                        @include('partials.product-card', ['product' => $relatedProduct])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
