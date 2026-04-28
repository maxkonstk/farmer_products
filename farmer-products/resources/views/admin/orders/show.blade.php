@extends('layouts.admin')

@section('title', "Заказ {$order->order_number}")
@section('page-title', "Заказ {$order->order_number}")
@section('page-subtitle', 'Операционный просмотр заказа: контакты, логистика, состав и быстрые смены статуса')

@section('content')
    <div class="detail-grid">
        <article class="content-card">
            <div class="section-heading section-heading--compact">
                <div>
                    <p class="eyebrow">Покупатель и логистика</p>
                    <h2 class="section-title">Информация о заказе</h2>
                </div>
            </div>

            <div class="stack-list">
                <div class="stack-list__item">
                    <div>
                        <strong>Покупатель</strong>
                        <p>{{ $order->customer_name }}</p>
                        @if ($order->user)
                            <p class="stack-list__meta">Авторизованный аккаунт: {{ $order->user->name }}</p>
                        @endif
                    </div>
                </div>
                <div class="stack-list__item">
                    <div>
                        <strong>Контакты</strong>
                        <p><a href="tel:{{ preg_replace('/[^0-9+]/', '', $order->phone) }}" class="table-link">{{ $order->phone }}</a></p>
                        <p><a href="mailto:{{ $order->email }}" class="table-link">{{ $order->email }}</a></p>
                    </div>
                </div>
                <div class="stack-list__item">
                    <div>
                        <strong>Сводка</strong>
                        <p>{{ $order->created_at->format('d.m.Y H:i') }} · {{ $order->items->count() }} {{ trans_choice('позиция|позиции|позиций', $order->items->count()) }}</p>
                        <p class="stack-list__meta">Итоговая сумма: {{ number_format((float) $order->total_price, 0, ',', ' ') }} ₽</p>
                    </div>
                </div>
                <div class="stack-list__item">
                    <div>
                        <strong>Получение</strong>
                        <p>{{ $order->fulfillment_method === 'pickup' ? 'Самовывоз' : 'Доставка' }}</p>
                        @if ($order->delivery_window)
                            <p class="stack-list__meta">{{ $shopDelivery['windows'][$order->delivery_window] ?? $order->delivery_window }}</p>
                        @endif
                    </div>
                </div>
                <div class="stack-list__item">
                    <div>
                        <strong>Адрес</strong>
                        <p>{{ $order->address }}</p>
                    </div>
                </div>
                @if ($order->substitution_preference)
                    <div class="stack-list__item">
                        <div>
                            <strong>Замены</strong>
                            <p>{{ $order->substitution_preference === 'best-match' ? 'Подобрать близкую замену' : ($order->substitution_preference === 'remove' ? 'Убрать отсутствующую позицию' : 'Сначала связаться с покупателем') }}</p>
                        </div>
                    </div>
                @endif
                @if ($order->comment)
                    <div class="stack-list__item">
                        <div>
                            <strong>Комментарий покупателя</strong>
                            <p>{{ $order->comment }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </article>

        <article class="content-card">
            <div class="section-heading section-heading--compact">
                <div>
                    <p class="eyebrow">Операционный статус</p>
                    <h2 class="section-title">Статус заказа</h2>
                </div>
            </div>

            <div class="content-card__status">
                @include('partials.order-status', ['status' => $order->status])
            </div>

            <div class="admin-quick-actions">
                @foreach ($statuses as $value => $label)
                    @continue($order->status->value === $value)

                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="admin-quick-actions__form">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $value }}">
                        <button type="submit" class="btn btn-ghost admin-quick-actions__button">{{ $label }}</button>
                    </form>
                @endforeach
            </div>
            <p class="form-hint">Быстрые действия меняют статус без выпадающего списка. Ниже остаётся полный ручной control.</p>

            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="stack-form">
                @csrf
                @method('PATCH')
                <label for="status" class="form-label">Новый статус</label>
                <select id="status" name="status" class="form-control">
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($order->status->value === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Сохранить статус</button>
            </form>
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
@endsection
