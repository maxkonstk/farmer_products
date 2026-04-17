@extends('layouts.site')

@section('title', 'Заказ оформлен')

@section('content')
    <section class="page-section">
        <div class="site-container">
            <div class="success-card">
                <p class="eyebrow">Заказ успешно оформлен</p>
                <h1 class="page-title">Спасибо за покупку</h1>
                <p class="page-subtitle">Номер заказа: <strong>{{ $order->order_number }}</strong>. Менеджер свяжется с вами для подтверждения и уточнения деталей доставки.</p>

                <div class="success-card__meta">
                    <div>
                        <span>Получатель</span>
                        <strong>{{ $order->customer_name }}</strong>
                    </div>
                    <div>
                        <span>Статус</span>
                        @include('partials.order-status', ['status' => $order->status])
                    </div>
                    <div>
                        <span>Сумма</span>
                        <strong>{{ number_format((float) $order->total_price, 0, ',', ' ') }} ₽</strong>
                    </div>
                </div>

                <div class="order-list">
                    @foreach ($order->items as $item)
                        <div class="checkout-item">
                            <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                            <strong>{{ number_format((float) $item->price * $item->quantity, 0, ',', ' ') }} ₽</strong>
                        </div>
                    @endforeach
                </div>

                <div class="hero-actions">
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary">Продолжить покупки</a>
                    @auth
                        <a href="{{ route('account.orders.index') }}" class="btn btn-outline">История заказов</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
@endsection
