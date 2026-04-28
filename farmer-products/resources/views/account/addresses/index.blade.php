@extends('layouts.site')

@section('title', 'Мои адреса')

@section('content')
    <section class="page-section">
        <div class="site-container">
            @include('account._nav')

            <div class="page-intro">
                <div>
                    <p class="eyebrow">Личный кабинет</p>
                    <h1 class="page-title">Адресная книга</h1>
                    <p class="page-subtitle">Сохраните часто используемые адреса, чтобы ускорить checkout и повторные заказы.</p>
                </div>
                <div class="hero-actions">
                    <a href="{{ route('account.addresses.create') }}" class="btn btn-primary">Добавить адрес</a>
                </div>
            </div>

            @if ($addresses->isEmpty())
                <div class="empty-state">
                    <h2>Адресов пока нет</h2>
                    <p>Добавьте первый адрес доставки для более быстрого оформления заказов.</p>
                    <a href="{{ route('account.addresses.create') }}" class="btn btn-primary">Создать адрес</a>
                </div>
            @else
                <div class="address-grid">
                    @foreach ($addresses as $address)
                        <article class="content-card address-card">
                            <div class="address-card__head">
                                <div>
                                    <h2>{{ $address->label }}</h2>
                                    @if ($address->is_default)
                                        <span class="address-card__badge">По умолчанию</span>
                                    @endif
                                </div>
                                <div class="table-actions">
                                    <a href="{{ route('account.addresses.edit', $address) }}" class="table-link">Изменить</a>
                                    <form method="POST" action="{{ route('account.addresses.destroy', $address) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost">Удалить</button>
                                    </form>
                                </div>
                            </div>

                            <div class="address-card__body">
                                @if ($address->recipient_name)
                                    <p><strong>{{ $address->recipient_name }}</strong></p>
                                @endif
                                @if ($address->phone)
                                    <p>{{ $address->phone }}</p>
                                @endif
                                <p>{{ $address->formatted_address }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
