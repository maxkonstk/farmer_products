@extends('layouts.admin')

@section('title', 'Настройки витрины')
@section('page-title', 'Настройки витрины')
@section('page-subtitle', 'Бренд, доставка и trust-блоки, которые управляют публичной частью магазина')

@section('content')
    <form method="POST" action="{{ route('admin.storefront.update') }}" class="form-card">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label for="brand_name" class="form-label">Название бренда</label>
                <input id="brand_name" type="text" name="brand_name" value="{{ old('brand_name', $settings['brand']['name']) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="brand_city" class="form-label">Город</label>
                <input id="brand_city" type="text" name="brand_city" value="{{ old('brand_city', $settings['brand']['city']) }}" class="form-control" required>
            </div>
            <div class="form-group form-group--full">
                <label for="brand_tagline" class="form-label">Tagline</label>
                <input id="brand_tagline" type="text" name="brand_tagline" value="{{ old('brand_tagline', $settings['brand']['tagline']) }}" class="form-control" required>
            </div>
            <div class="form-group form-group--full">
                <label for="hero_note" class="form-label">Hero note</label>
                <textarea id="hero_note" name="hero_note" rows="3" class="form-control" required>{{ old('hero_note', $settings['brand']['hero_note']) }}</textarea>
            </div>
            <div class="form-group">
                <label for="brand_address" class="form-label">Адрес</label>
                <input id="brand_address" type="text" name="brand_address" value="{{ old('brand_address', $settings['brand']['address']) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="brand_phone" class="form-label">Телефон</label>
                <input id="brand_phone" type="text" name="brand_phone" value="{{ old('brand_phone', $settings['brand']['phone']) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="brand_email" class="form-label">Email</label>
                <input id="brand_email" type="email" name="brand_email" value="{{ old('brand_email', $settings['brand']['email']) }}" class="form-control" required>
            </div>
            <div class="form-group form-group--full">
                <label for="brand_hours" class="form-label">Часы работы</label>
                <textarea id="brand_hours" name="brand_hours" rows="4" class="form-control" required>{{ old('brand_hours', $brandHoursText) }}</textarea>
                <p class="form-hint">Одна строка = один интервал.</p>
            </div>

            <div class="form-group form-group--full">
                <label for="delivery_cutoff" class="form-label">Сообщение про cut-off</label>
                <input id="delivery_cutoff" type="text" name="delivery_cutoff" value="{{ old('delivery_cutoff', $settings['delivery']['cutoff']) }}" class="form-control" required>
            </div>
            <div class="form-group form-group--full">
                <label for="pickup_address" class="form-label">Адрес самовывоза</label>
                <input id="pickup_address" type="text" name="pickup_address" value="{{ old('pickup_address', $settings['delivery']['pickup_address']) }}" class="form-control" required>
            </div>
            <div class="form-group form-group--full">
                <label for="delivery_windows" class="form-label">Окна доставки</label>
                <textarea id="delivery_windows" name="delivery_windows" rows="6" class="form-control" required>{{ old('delivery_windows', $deliveryWindowsText) }}</textarea>
                <p class="form-hint">Формат строки: `ключ | Подпись`.</p>
            </div>
            <div class="form-group form-group--full">
                <label for="delivery_zones" class="form-label">Зоны доставки</label>
                <textarea id="delivery_zones" name="delivery_zones" rows="5" class="form-control" required>{{ old('delivery_zones', $deliveryZonesText) }}</textarea>
            </div>
            <div class="form-group form-group--full">
                <label for="delivery_promises" class="form-label">Обещания доставки</label>
                <textarea id="delivery_promises" name="delivery_promises" rows="5" class="form-control" required>{{ old('delivery_promises', $deliveryPromisesText) }}</textarea>
            </div>
            <div class="form-group form-group--full">
                <label for="storefront_promises" class="form-label">Trust-блок на витрине</label>
                <textarea id="storefront_promises" name="storefront_promises" rows="5" class="form-control" required>{{ old('storefront_promises', $storefrontPromisesText) }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Сохранить настройки</button>
    </form>
@endsection
