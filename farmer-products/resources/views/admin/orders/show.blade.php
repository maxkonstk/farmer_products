@extends('layouts.admin')

@section('title', "Заказ {$order->order_number}")
@section('page-title', "Заказ {$order->order_number}")
@section('page-subtitle', 'Просмотр состава заказа и изменение статуса')

@section('content')
    <div class="detail-grid">
        <article class="content-card">
            <h2>Информация о заказе</h2>
            <p><strong>Покупатель:</strong> {{ $order->customer_name }}</p>
            <p><strong>Телефон:</strong> {{ $order->phone }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            <p><strong>Адрес:</strong> {{ $order->address }}</p>
            @if ($order->comment)
                <p><strong>Комментарий:</strong> {{ $order->comment }}</p>
            @endif
        </article>

        <article class="content-card">
            <h2>Статус заказа</h2>
            <div class="content-card__status">
                @include('partials.order-status', ['status' => $order->status])
            </div>

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
