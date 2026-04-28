@extends('layouts.admin')

@section('title', 'Обзор')
@section('page-title', 'Обзор магазина')
@section('page-subtitle', 'Ключевые показатели, новые заказы и товары с низким остатком')
@section('page-note-title', 'Редакционный контроль')
@section('page-note', 'На одном экране собраны метрики магазина, свежие заказы и товары, которым уже нужен новый остаток.')

@section('content')
    <div class="stats-grid">
        <article class="stat-card">
            <span>Категории</span>
            <strong>{{ $stats['categories'] }}</strong>
        </article>
        <article class="stat-card">
            <span>Товары</span>
            <strong>{{ $stats['products'] }}</strong>
        </article>
        <article class="stat-card">
            <span>Коллекции</span>
            <strong>{{ $stats['collections'] }}</strong>
        </article>
        <article class="stat-card">
            <span>Заказы</span>
            <strong>{{ $stats['orders'] }}</strong>
        </article>
        <article class="stat-card">
            <span>Новые заказы</span>
            <strong>{{ $stats['new_orders'] }}</strong>
        </article>
        <article class="stat-card">
            <span>Сумма заказов</span>
            <strong>{{ number_format((float) $stats['revenue'], 0, ',', ' ') }} ₽</strong>
        </article>
        <article class="stat-card">
            <span>Фермеры</span>
            <strong>{{ $stats['farmers'] }}</strong>
        </article>
        <article class="stat-card">
            <span>Отзывы</span>
            <strong>{{ $stats['testimonials'] }}</strong>
        </article>
        <article class="stat-card">
            <span>FAQ</span>
            <strong>{{ $stats['faq_items'] }}</strong>
        </article>
        <article class="stat-card">
            <span>Промо-блоки</span>
            <strong>{{ $stats['promo_blocks'] }}</strong>
        </article>
        <article class="stat-card">
            <span>Активные промо</span>
            <strong>{{ $stats['active_promos'] }}</strong>
        </article>
    </div>

    <div class="admin-grid">
        <section class="table-card">
            <div class="section-heading section-heading--compact">
                <div>
                    <p class="eyebrow">Последние заказы</p>
                    <h2 class="section-title">Новые обращения покупателей</h2>
                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Покупатель</th>
                        <th>Статус</th>
                        <th>Позиций</th>
                        <th>Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentOrders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}" class="table-link">{{ $order->order_number }}</a></td>
                            <td>{{ $order->customer_name }}</td>
                            <td>@include('partials.order-status', ['status' => $order->status])</td>
                            <td>{{ $order->items_count }}</td>
                            <td>{{ number_format((float) $order->total_price, 0, ',', ' ') }} ₽</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <section class="content-card">
            <div class="section-heading section-heading--compact">
                <div>
                    <p class="eyebrow">Остатки</p>
                    <h2 class="section-title">Товары, требующие внимания</h2>
                </div>
            </div>

            <div class="stack-list">
                @forelse ($lowStockProducts as $product)
                    <div class="stack-list__item">
                        <div>
                            <strong>{{ $product->name }}</strong>
                            <p>{{ $product->category->name }}</p>
                        </div>
                        <span class="status-badge status-badge--cancelled">{{ $product->stock }} шт.</span>
                    </div>
                @empty
                    <p>Все товары имеют достаточный остаток.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
