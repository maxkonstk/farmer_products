@extends('layouts.site')

@section('title', 'Корзина')

@php($analytics = app(\App\Services\AnalyticsService::class))

@push('analytics_events')
    @if (! $is_empty)
        @include('partials.analytics-event', [
            'event' => [
                'event' => 'view_cart',
                'ecommerce' => [
                    'currency' => 'RUB',
                    'value' => round((float) $total_price, 2),
                    'items' => $analytics->itemsFromCart($items),
                ],
            ],
        ])
    @endif
@endpush

@section('content')
    <section class="page-section {{ $is_empty ? '' : 'page-section--has-mobile-summary' }}">
        <div class="site-container">
            <div class="page-intro">
                <div>
                    <p class="eyebrow">Корзина</p>
                    <h1 class="page-title">Проверьте состав заказа перед подтверждением</h1>
                    <p class="page-subtitle">На этом этапе удобно поправить количество, убрать позиции и убедиться, что корзина подходит под ваш слот доставки.</p>
                </div>
            </div>

            @if ($is_empty)
                <div class="empty-state">
                    <h2>Корзина пока пуста</h2>
                    <p>Соберите первую корзину из молочки, овощей, зелени и сезонных позиций — после этого появится оформление заказа и выбор способа получения.</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary">Перейти в каталог</a>
                </div>
            @else
                <div class="cart-layout">
                    <div class="cart-items">
                        @foreach ($items as $item)
                            @php($imageAttributes = \App\Support\ImageMetadata::attributes($item['product']->image_url))
                            <article class="cart-item">
                                <img
                                    src="{{ $item['product']->image_url }}"
                                    alt="{{ $item['product']->name }}"
                                    class="cart-item__image"
                                    loading="lazy"
                                    decoding="async"
                                    width="{{ $imageAttributes['width'] }}"
                                    height="{{ $imageAttributes['height'] }}"
                                    sizes="(max-width: 900px) 8rem, 9rem"
                                >
                                <div class="cart-item__content">
                                    <div>
                                        <h2 class="cart-item__title">{{ $item['product']->name }}</h2>
                                        <p class="cart-item__meta">{{ $item['product']->category->name }} · {{ $item['product']->weight ?: 'Вес уточняется' }}</p>
                                        @if ($item['product']->delivery_note)
                                            <p class="cart-item__hint">{{ $item['product']->delivery_note }}</p>
                                        @endif
                                    </div>

                                    <div class="cart-item__actions">
                                        <form method="POST" action="{{ route('cart.update', $item['product']) }}" class="cart-quantity-form">
                                            @csrf
                                            @method('PATCH')
                                            <label for="quantity-{{ $item['product']->id }}" class="sr-only">Количество товара {{ $item['product']->name }}</label>
                                            <input id="quantity-{{ $item['product']->id }}" type="number" name="quantity" min="1" max="{{ $item['product']->stock }}" value="{{ $item['quantity'] }}" class="form-control form-control--small" inputmode="numeric" enterkeyhint="done">
                                            <button type="submit" class="btn btn-outline">Обновить</button>
                                        </form>

                                        <form method="POST" action="{{ route('cart.destroy', $item['product']) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost">Удалить</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="cart-item__price">
                                    {{ number_format((float) $item['line_total'], 0, ',', ' ') }} ₽
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <aside class="summary-card">
                        <h2>Итоги заказа</h2>
                        <div class="summary-card__row">
                            <span>Количество товаров</span>
                            <strong>{{ $total_quantity }}</strong>
                        </div>
                        <div class="summary-card__row">
                            <span>Общая сумма</span>
                            <strong>{{ number_format((float) $total_price, 0, ',', ' ') }} ₽</strong>
                        </div>
                        <div class="summary-card__trust">
                            <p>Что дальше:</p>
                            <ul class="info-list">
                                <li>Выберете доставку или самовывоз на следующем шаге.</li>
                                <li>Согласуем замены, если каких-то позиций не хватит в партии.</li>
                                <li>Подтвердим заказ вручную и уточним слот звонком.</li>
                            </ul>
                        </div>
                        <div class="summary-card__actions">
                            <a href="{{ route('checkout.create') }}" class="btn btn-primary btn-full">Оформить заказ</a>
                            <form method="POST" action="{{ route('cart.clear') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-full">Очистить корзину</button>
                            </form>
                        </div>
                    </aside>
                </div>

                <div class="mobile-summary-bar" aria-label="Быстрое оформление заказа">
                    <div class="mobile-summary-bar__meta">
                        <span>{{ $total_quantity }} позиций</span>
                        <strong>{{ number_format((float) $total_price, 0, ',', ' ') }} ₽</strong>
                    </div>
                    <a href="{{ route('checkout.create') }}" class="btn btn-primary">Перейти к оформлению</a>
                </div>
            @endif
        </div>
    </section>
@endsection
