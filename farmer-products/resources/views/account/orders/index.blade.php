@extends('layouts.site')

@section('title', 'Мои заказы')

@section('content')
    <section class="page-section">
        <div class="site-container">
            @include('account._nav')

            <div class="page-intro">
                <div>
                    <p class="eyebrow">Личный кабинет</p>
                    <h1 class="page-title">История заказов</h1>
                    <p class="page-subtitle">Здесь отображаются все заказы авторизованного пользователя.</p>
                </div>
            </div>

            @if ($orders->isEmpty())
                <div class="empty-state">
                    <h2>У вас еще нет заказов</h2>
                    <p>Добавьте товары в корзину и оформите первую покупку.</p>
                </div>
            @else
                <div class="table-card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>№ заказа</th>
                                <th>Дата</th>
                                <th>Статус</th>
                                <th>Позиций</th>
                                <th>Сумма</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                    <td>@include('partials.order-status', ['status' => $order->status])</td>
                                    <td>{{ $order->items_count }}</td>
                                    <td>{{ number_format((float) $order->total_price, 0, ',', ' ') }} ₽</td>
                                    <td class="table-actions">
                                        <a href="{{ route('account.orders.show', $order) }}" class="table-link">Подробнее</a>
                                        <form method="POST" action="{{ route('account.orders.repeat', $order) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-ghost">Повторить</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrap">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
