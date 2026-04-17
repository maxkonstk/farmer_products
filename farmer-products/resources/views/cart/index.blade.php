@extends('layouts.site')

@section('title', 'Корзина')

@section('content')
    <section class="page-section">
        <div class="site-container">
            <div class="page-intro">
                <div>
                    <p class="eyebrow">Корзина покупателя</p>
                    <h1 class="page-title">Ваш заказ</h1>
                    <p class="page-subtitle">Изменяйте количество, удаляйте позиции и переходите к оформлению заказа.</p>
                </div>
            </div>

            @if ($is_empty)
                <div class="empty-state">
                    <h2>Корзина пока пуста</h2>
                    <p>Добавьте товары из каталога, чтобы оформить заказ.</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary">Перейти в каталог</a>
                </div>
            @else
                <div class="cart-layout">
                    <div class="cart-items">
                        @foreach ($items as $item)
                            <article class="cart-item">
                                <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}" class="cart-item__image">
                                <div class="cart-item__content">
                                    <div>
                                        <h2 class="cart-item__title">{{ $item['product']->name }}</h2>
                                        <p class="cart-item__meta">{{ $item['product']->category->name }} · {{ $item['product']->weight ?: 'Вес уточняется' }}</p>
                                    </div>

                                    <div class="cart-item__actions">
                                        <form method="POST" action="{{ route('cart.update', $item['product']) }}" class="cart-quantity-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" min="1" max="{{ $item['product']->stock }}" value="{{ $item['quantity'] }}" class="form-control form-control--small">
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
            @endif
        </div>
    </section>
@endsection
