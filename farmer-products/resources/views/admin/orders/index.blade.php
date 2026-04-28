@extends('layouts.admin')

@section('title', 'Заказы')
@section('page-title', 'Заказы покупателей')
@section('page-subtitle', 'Поток заказов с поиском по клиенту, контакту и способу получения')
@section('page-note-title', 'Поток заказов')
@section('page-note', 'Здесь оператор быстро находит заказ по номеру, телефону или email, отделяет доставку от самовывоза и двигает статусы без лишних переходов.')

@section('content')
    <form method="GET" class="toolbar admin-toolbar">
        <div class="form-group admin-toolbar__field">
            <label for="q" class="form-label">Поиск</label>
            <input id="q" type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Номер заказа, имя, телефон или email">
        </div>
        <div class="form-group admin-toolbar__field">
            <label for="status" class="form-label">Статус заказа</label>
            <select id="status" name="status" class="form-control">
                <option value="">Все статусы</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group admin-toolbar__field">
            <label for="fulfillment_method" class="form-label">Получение</label>
            <select id="fulfillment_method" name="fulfillment_method" class="form-control">
                <option value="">Все сценарии</option>
                @foreach ($fulfillmentOptions as $value => $label)
                    <option value="{{ $value }}" @selected($fulfillmentMethod === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-panel__actions admin-toolbar__actions">
            <button type="submit" class="btn btn-outline">Применить</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost">Сбросить</a>
        </div>
    </form>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Покупатель</th>
                    <th>Получение</th>
                    <th>Статус</th>
                    <th>Позиций</th>
                    <th>Дата</th>
                    <th>Сумма</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>
                            <strong>{{ $order->customer_name }}</strong>
                            <span class="table-cell__meta">{{ $order->phone }} · {{ $order->email }}</span>
                        </td>
                        <td>{{ $order->fulfillment_method === 'pickup' ? 'Самовывоз' : 'Доставка' }}</td>
                        <td>@include('partials.order-status', ['status' => $order->status])</td>
                        <td>{{ $order->items_count }}</td>
                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td>{{ number_format((float) $order->total_price, 0, ',', ' ') }} ₽</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="table-link">Открыть</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Заказы по текущим фильтрам не найдены.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $orders->links() }}
    </div>
@endsection
