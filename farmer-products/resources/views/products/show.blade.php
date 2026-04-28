@extends('layouts.site')

@section('title', $product->name)
@section('og_type', 'product')
@section('meta_description', $product->description)
@section('meta_image', $product->gallery_urls[0])

@php($isFavorited = in_array($product->id, $favoriteProductIds ?? [], true))

@push('structured_data')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->description,
            'image' => $product->gallery_urls,
            'category' => $product->category->name,
            'brand' => [
                '@type' => 'Brand',
                'name' => $shopBrand['name'],
            ],
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'RUB',
                'price' => (float) $product->price,
                'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => route('products.show', $product),
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <section class="page-section">
        <div class="site-container product-detail product-detail--commerce">
            <div class="product-gallery">
                <div class="product-gallery__main">
                    <img src="{{ $product->gallery_urls[0] }}" alt="{{ $product->name }}" class="product-detail__image">
                </div>
                <div class="product-gallery__thumbs">
                    @foreach ($product->gallery_urls as $galleryImage)
                        <div class="product-gallery__thumb">
                            <img src="{{ $galleryImage }}" alt="{{ $product->name }}">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="product-detail__content">
                <div class="product-story-meta">
                    <p class="eyebrow">{{ $product->category->name }}</p>
                    @if ($product->badge)
                        <span class="product-story-meta__tag">{{ $product->badge }}</span>
                    @endif
                </div>
                <h1 class="page-title">{{ $product->name }}</h1>
                <p class="page-subtitle">{{ $product->description }}</p>

                <div class="product-facts-grid">
                    <div class="detail-fact">
                        <span>Цена</span>
                        <strong>{{ number_format((float) $product->price, 0, ',', ' ') }} ₽</strong>
                    </div>
                    <div class="detail-fact">
                        <span>Вес / объем</span>
                        <strong>{{ $product->weight ?: 'Уточняется' }}</strong>
                    </div>
                    <div class="detail-fact">
                        <span>Поставщик</span>
                        <strong>{{ $product->producer_name ?: 'Уточним при подтверждении' }}</strong>
                    </div>
                    <div class="detail-fact">
                        <span>Регион</span>
                        <strong>{{ $product->origin_location ?: 'Самарская область' }}</strong>
                    </div>
                </div>

                <aside class="product-buy-card">
                    <div class="product-buy-card__head">
                        <div>
                            <span class="product-buy-card__label">Наличие</span>
                            <strong>{{ $product->stock > 0 ? "Осталось {$product->stock} шт." : 'Сейчас нет в наличии' }}</strong>
                        </div>
                        <div>
                            <span class="product-buy-card__label">Доставка</span>
                            <strong>{{ $product->delivery_note ?: 'Подтвердим слот после звонка' }}</strong>
                        </div>
                    </div>

                    <div class="product-buy-card__actions">
                        @if ($product->stock > 0)
                            <form method="POST" action="{{ route('cart.store', $product) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-large btn-full">Добавить в корзину</button>
                            </form>
                        @else
                            <button type="button" class="btn btn-disabled btn-large btn-full" disabled>Временно недоступно</button>
                        @endif
                        <a href="{{ route('checkout.create') }}" class="btn btn-outline btn-large btn-full">Перейти к оформлению</a>
                        @auth
                            @if ($isFavorited)
                                <form method="POST" action="{{ route('account.favorites.destroy', $product) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-full">Убрать из избранного</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('account.favorites.store', $product) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost btn-full">Сохранить в избранное</button>
                                </form>
                            @endif
                        @endauth
                    </div>

                    <ul class="info-list">
                        @foreach ($product->highlights_list as $highlight)
                            <li>{{ $highlight }}</li>
                        @endforeach
                    </ul>
                </aside>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="site-container page-columns">
            <article class="product-section-card">
                <p class="eyebrow">Происхождение и вкус</p>
                <h2>Что важно знать до покупки</h2>
                @if ($product->collections->isNotEmpty())
                    <div class="product-collection-list">
                        @foreach ($product->collections as $collection)
                            <a href="{{ route('collections.show', $collection) }}" class="category-pill">
                                {{ $collection->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
                <dl class="meta-list">
                    <div>
                        <dt>Хозяйство</dt>
                        <dd>{{ $product->producer_name ?: 'Подберем поставщика под текущую партию' }}</dd>
                    </div>
                    <div>
                        <dt>Регион</dt>
                        <dd>{{ $product->origin_location ?: 'Самарская область' }}</dd>
                    </div>
                    <div>
                        <dt>Сезонность</dt>
                        <dd>{{ $product->seasonality ?: 'Круглый год' }}</dd>
                    </div>
                    <div>
                        <dt>Вкус и текстура</dt>
                        <dd>{{ $product->taste_notes ?: 'Натуральный вкус без длинного хранения и лишней обработки.' }}</dd>
                    </div>
                </dl>
            </article>

            <article class="product-section-card">
                <p class="eyebrow">Хранение и состав</p>
                <h2>Как лучше использовать продукт</h2>
                <dl class="meta-list">
                    <div>
                        <dt>Хранение</dt>
                        <dd>{{ $product->storage_info ?: 'Условия хранения подскажем при подтверждении заказа.' }}</dd>
                    </div>
                    <div>
                        <dt>Срок после доставки</dt>
                        <dd>{{ $product->shelf_life ?: 'Зависит от партии и способа хранения.' }}</dd>
                    </div>
                    <div>
                        <dt>Состав</dt>
                        <dd>{{ $product->ingredients ?: 'Без лишних добавок и промышленной стабилизации.' }}</dd>
                    </div>
                    <div>
                        <dt>Замены</dt>
                        <dd>Если позиции не окажется в доступном остатке, согласуем замену заранее или уберем товар из заказа.</dd>
                    </div>
                </dl>
            </article>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="section section--muted">
            <div class="site-container">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow">В той же категории</p>
                        <h2 class="section-title">Что еще покупают вместе с этой позицией</h2>
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

    @if ($recommendedProducts->isNotEmpty())
        <section class="section">
            <div class="site-container">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow">Для полной корзины</p>
                        <h2 class="section-title">Рекомендуем добавить к заказу</h2>
                    </div>
                </div>

                <div class="product-grid">
                    @foreach ($recommendedProducts as $recommendedProduct)
                        @include('partials.product-card', ['product' => $recommendedProduct])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
