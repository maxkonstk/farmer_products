@extends('layouts.site')

@section('title', 'Оформление заказа')

@section('content')
    <section class="page-section">
        <div
            class="site-container checkout-layout"
            x-data="{
                selectedAddress: @js((string) old('saved_address_id', $defaultAddress?->id ?? '')),
                address: @js(old('address', $defaultAddress?->formatted_address ?? '')),
                syncAddress(event) {
                    const option = event.target.selectedOptions[0];

                    if (! option || ! option.dataset.address) {
                        return;
                    }

                    this.address = option.dataset.address;
                }
            }"
        >
            <div>
                <p class="eyebrow">Оформление заказа</p>
                <h1 class="page-title">Подтвердите контактные данные и удобный способ получения</h1>
                <p class="page-subtitle">После отправки мы вручную подтверждаем заказ, проверяем остатки по партии и связываемся с вами до отгрузки.</p>

                <form method="POST" action="{{ route('checkout.store') }}" class="form-card">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group form-group--full">
                            <label class="form-label">Способ получения</label>
                            <div class="checkout-option-grid">
                                <label class="checkout-option">
                                    <input type="radio" name="fulfillment_method" value="delivery" @checked(old('fulfillment_method', 'delivery') === 'delivery')>
                                    <span>Доставка по Самаре</span>
                                </label>
                                <label class="checkout-option">
                                    <input type="radio" name="fulfillment_method" value="pickup" @checked(old('fulfillment_method') === 'pickup')>
                                    <span>Самовывоз из лавки</span>
                                </label>
                            </div>
                        </div>

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
                        <div class="form-group">
                            <label for="delivery_window" class="form-label">Желаемое окно</label>
                            <select id="delivery_window" name="delivery_window" class="form-control">
                                <option value="">Подберем с менеджером</option>
                                @foreach (config('shop.delivery.windows', []) as $windowKey => $windowLabel)
                                    <option value="{{ $windowKey }}" @selected(old('delivery_window') === $windowKey)>{{ $windowLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="substitution_preference" class="form-label">Если товара не хватит</label>
                            <select id="substitution_preference" name="substitution_preference" class="form-control">
                                <option value="">Согласовать со мной</option>
                                <option value="call" @selected(old('substitution_preference') === 'call')>Позвонить перед заменой</option>
                                <option value="best-match" @selected(old('substitution_preference') === 'best-match')>Подобрать близкую замену</option>
                                <option value="remove" @selected(old('substitution_preference') === 'remove')>Убрать товар из заказа</option>
                            </select>
                        </div>
                        @if ($user && $savedAddresses->isNotEmpty())
                            <div class="form-group form-group--full">
                                <label for="saved_address_id" class="form-label">Сохраненный адрес</label>
                                <select
                                    id="saved_address_id"
                                    name="saved_address_id"
                                    class="form-control"
                                    x-model="selectedAddress"
                                    @change="syncAddress($event)"
                                >
                                    <option value="">Ввести адрес вручную</option>
                                    @foreach ($savedAddresses as $savedAddress)
                                        <option
                                            value="{{ $savedAddress->id }}"
                                            data-address="{{ $savedAddress->formatted_address }}"
                                            @selected((string) old('saved_address_id', $defaultAddress?->id) === (string) $savedAddress->id)
                                        >
                                            {{ $savedAddress->label }} · {{ $savedAddress->formatted_address }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="form-hint">Адреса можно редактировать в <a href="{{ route('account.addresses.index') }}">личном кабинете</a>.</p>
                            </div>
                        @elseif ($user)
                            <div class="form-group form-group--full">
                                <p class="form-hint">У вас пока нет сохраненных адресов. <a href="{{ route('account.addresses.create') }}">Добавить адрес</a></p>
                            </div>
                        @endif
                        <div class="form-group form-group--full">
                            <label for="address" class="form-label">Адрес доставки</label>
                            <textarea id="address" name="address" rows="3" class="form-control" x-model="address">{{ old('address', $defaultAddress?->formatted_address) }}</textarea>
                            <p class="form-hint">Для самовывоза адрес можно не заполнять: заказ будет ждать вас по адресу {{ config('shop.delivery.pickup_address') }}.</p>
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
                <div class="summary-card__trust">
                    <p>Что входит в сервис:</p>
                    <ul class="info-list">
                        @foreach (config('shop.delivery.promises', []) as $promise)
                            <li>{{ $promise }}</li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </section>
@endsection
