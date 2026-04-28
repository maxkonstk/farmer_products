@extends('layouts.site')

@section('title', "Заказ {$order->order_number}")

@section('content')
    <section class="page-section">
        <div class="site-container">
            @include('account._nav')

            <div class="page-intro">
                <div>
                    <p class="eyebrow">Детали заказа</p>
                    <h1 class="page-title">Заказ {{ $order->order_number }}</h1>
                    <p class="page-subtitle">Дата оформления: {{ $order->created_at->format('d.m.Y H:i') }}</p>
                </div>
                <div class="hero-actions">
                    @include('partials.order-status', ['status' => $order->status])
                    <form method="POST" action="{{ route('account.orders.repeat', $order) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline">Повторить заказ</button>
                    </form>
                </div>
            </div>

            <div class="detail-grid">
                <article class="content-card">
                    <h2>Данные получателя</h2>
                    <p><strong>{{ $order->customer_name }}</strong></p>
                    <p>{{ $order->phone }}</p>
                    <p>{{ $order->email }}</p>
                    <p>{{ $order->address }}</p>
                    <p><strong>Способ получения:</strong> {{ $order->fulfillment_method === 'pickup' ? 'Самовывоз' : 'Доставка' }}</p>
                    @if ($order->delivery_window)
                        <p><strong>Окно:</strong> {{ config('shop.delivery.windows')[$order->delivery_window] ?? $order->delivery_window }}</p>
                    @endif
                </article>
                <article class="content-card">
                    <h2>Сумма заказа</h2>
                    <p class="summary-number">{{ number_format((float) $order->total_price, 0, ',', ' ') }} ₽</p>
                    @if ($order->substitution_preference)
                        <p><strong>Замены:</strong> {{ $order->substitution_preference === 'best-match' ? 'разрешены близкие замены' : ($order->substitution_preference === 'remove' ? 'убирать отсутствующие позиции' : 'сначала позвонить') }}</p>
                    @endif
                    @if ($order->comment)
                        <p>{{ $order->comment }}</p>
                    @endif
                </article>
            </div>

            <div class="table-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Количество</th>
                            <th>Цена</th>
                            <th>Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format((float) $item->price, 0, ',', ' ') }} ₽</td>
                                <td>{{ number_format((float) $item->price * $item->quantity, 0, ',', ' ') }} ₽</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
