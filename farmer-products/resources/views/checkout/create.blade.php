@extends('layouts.site')

@section('title', 'Оформление заказа')

@section('content')
    <section class="page-section">
        <div class="site-container checkout-layout">
            <div>
                <p class="eyebrow">Оформление заказа</p>
                <h1 class="page-title">Заполните данные получателя</h1>
                <p class="page-subtitle">После отправки формы заказ сохраняется в базе данных, а товары списываются со склада.</p>

                <form method="POST" action="{{ route('checkout.store') }}" class="form-card">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="customer_name" class="form-label">ФИО</label>
                            <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name', $user?->name) }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="form-label">Телефон</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone', $user?->phone) }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user?->email) }}" class="form-control" required>
                        </div>
                        <div class="form-group form-group--full">
                            <label for="address" class="form-label">Адрес доставки</label>
                            <textarea id="address" name="address" rows="3" class="form-control" required>{{ old('address') }}</textarea>
                        </div>
                        <div class="form-group form-group--full">
                            <label for="comment" class="form-label">Комментарий к заказу</label>
                            <textarea id="comment" name="comment" rows="4" class="form-control">{{ old('comment') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-large">Подтвердить заказ</button>
                </form>
            </div>

            <aside class="summary-card">
                <h2>Состав заказа</h2>
                <div class="checkout-items">
                    @foreach ($items as $item)
                        <div class="checkout-item">
                            <span>{{ $item['product']->name }} × {{ $item['quantity'] }}</span>
                            <strong>{{ number_format((float) $item['line_total'], 0, ',', ' ') }} ₽</strong>
                        </div>
                    @endforeach
                </div>
                <div class="summary-card__row">
                    <span>Количество позиций</span>
                    <strong>{{ $total_quantity }}</strong>
                </div>
                <div class="summary-card__row">
                    <span>Итого к оплате</span>
                    <strong>{{ number_format((float) $total_price, 0, ',', ' ') }} ₽</strong>
                </div>
            </aside>
        </div>
    </section>
@endsection
