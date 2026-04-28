@extends('layouts.site')

@section('title', 'Заказ оформлен')

@php($analytics = app(\App\Services\AnalyticsService::class))

@push('analytics_events')
    @include('partials.analytics-event', [
        'event' => [
            'event' => 'purchase',
            'ecommerce' => [
                'currency' => 'RUB',
                'transaction_id' => $order->order_number,
                'value' => round((float) $order->total_price, 2),
                'shipping_tier' => $order->fulfillment_method,
                'items' => $analytics->itemsFromOrder($order),
            ],
        ],
    ])
@endpush

@section('content')
    <section class="page-section">
        <div class="site-container">
            <div class="success-card">
                <p class="eyebrow">Заказ успешно оформлен</p>
                <h1 class="page-title">Спасибо за покупку</h1>
                <p class="page-subtitle">Номер заказа: <strong>{{ $order->order_number }}</strong>. Мы уже сохранили состав корзины и свяжемся с вами для подтверждения деталей получения.</p>

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
                    <div>
                        <span>Получение</span>
                        <strong>{{ $order->fulfillment_method === 'pickup' ? 'Самовывоз' : 'Доставка' }}</strong>
                    </div>
                </div>

                <div class="summary-card__trust success-card__details">
                    <ul class="info-list">
                        <li>Окно получения: {{ $order->delivery_window ? ($shopDelivery['windows'][$order->delivery_window] ?? $order->delivery_window) : 'подтвердим при звонке' }}</li>
                        <li>Адрес: {{ $order->address }}</li>
                        <li>Замены: {{ match($order->substitution_preference) { 'best-match' => 'можно подобрать близкую замену', 'remove' => 'лучше убрать отсутствующую позицию', default => 'сначала связаться с покупателем' } }}</li>
                    </ul>
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
