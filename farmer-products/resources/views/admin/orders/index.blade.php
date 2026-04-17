@extends('layouts.admin')

@section('title', 'Заказы')
@section('page-title', 'Заказы покупателей')
@section('page-subtitle', 'Список заказов с фильтрацией по статусу')
@section('page-note-title', 'Поток заказов')
@section('page-note', 'Новые обращения покупателей, статусы и суммы собраны в табличной ленте, стилистически совпадающей с личным кабинетом и публичными разделами.')

@section('content')
    <form method="GET" class="toolbar admin-toolbar">
        <div class="form-group admin-toolbar__field">
            <label for="status" class="form-label">Статус заказа</label>
            <select id="status" name="status" class="form-control">
                <option value="">Все статусы</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-panel__actions admin-toolbar__actions">
            <button type="submit" class="btn btn-outline">Фильтровать</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost">Сбросить</a>
        </div>
    </form>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Покупатель</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th>Сумма</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>@include('partials.order-status', ['status' => $order->status])</td>
                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td>{{ number_format((float) $order->total_price, 0, ',', ' ') }} ₽</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="table-link">Открыть</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $orders->links() }}
    </div>
@endsection
