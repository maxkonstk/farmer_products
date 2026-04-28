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

        <section class="content-card">
            <div class="section-heading section-heading--compact">
                <div>
                    <p class="eyebrow">Launch readiness</p>
                    <h2 class="section-title">Analytics sink</h2>
                </div>
            </div>

            <div class="stack-list">
                <div class="stack-list__item">
                    <div>
                        <strong>Provider</strong>
                        <p>{{ match($analytics['provider'] ?? 'none') {
                            'ga4' => 'Google Analytics 4',
                            'gtm' => 'Google Tag Manager',
                            default => 'Не подключен',
                        } }}</p>
                    </div>
                    <span class="status-badge {{ ($analytics['provider'] ?? 'none') === 'none' ? 'status-badge--cancelled' : 'status-badge--completed' }}">
                        {{ strtoupper((string) ($analytics['provider'] ?? 'none')) }}
                    </span>
                </div>
                <div class="stack-list__item">
                    <div>
                        <strong>Идентификатор</strong>
                        <p>{{ $analytics['gtm_container_id'] ?? $analytics['ga_measurement_id'] ?? 'Не задан' }}</p>
                    </div>
                </div>
                <div class="stack-list__item">
                    <div>
                        <strong>Web vitals</strong>
                        <p>{{ ($analytics['track_web_vitals'] ?? false) ? 'LCP, CLS, FCP и TTFB отправляются в dataLayer.' : 'Сбор выключен.' }}</p>
                    </div>
                </div>
                <div class="stack-list__item">
                    <div>
                        <strong>Debug mode</strong>
                        <p>{{ ($analytics['debug_mode'] ?? false) ? 'Включен для проверки событиями.' : 'Выключен для production-трафика.' }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="content-card">
            <div class="section-heading section-heading--compact">
                <div>
                    <p class="eyebrow">Operations</p>
                    <h2 class="section-title">Health и readiness</h2>
                </div>
            </div>

            <div class="stack-list">
                <div class="stack-list__item">
                    <div>
                        <strong>Общий статус</strong>
                        <p>{{ $readiness['checked_at'] }}</p>
                    </div>
                    <span class="status-badge {{
                        match($readiness['status']) {
                            'ok' => 'status-badge--completed',
                            'warning' => 'status-badge--new',
                            default => 'status-badge--cancelled',
                        }
                    }}">
                        {{ strtoupper($readiness['status']) }}
                    </span>
                </div>
                @foreach ($readiness['checks'] as $checkName => $check)
                    <div class="stack-list__item">
                        <div>
                            <strong>{{ str($checkName)->replace('_', ' ')->title() }}</strong>
                            <p>{{ $check['message'] }}</p>
                            @if (! empty($check['meta']))
                                <p class="stack-list__meta">{{ json_encode($check['meta'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</p>
                            @endif
                        </div>
                        <span class="status-badge {{
                            match($check['status']) {
                                'ok' => 'status-badge--completed',
                                'warning' => 'status-badge--new',
                                default => 'status-badge--cancelled',
                            }
                        }}">
                            {{ strtoupper($check['status']) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
