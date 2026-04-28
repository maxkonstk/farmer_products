@extends('layouts.site')

@section('title', 'Личный кабинет')

@section('content')
    <section class="page-section">
        <div class="site-container">
            @include('account._nav')

            <div class="page-intro">
                <div>
                    <p class="eyebrow">Личный кабинет</p>
                    <h1 class="page-title">Панель пользователя</h1>
                    <p class="page-subtitle">Отсюда можно перейти к истории заказов, сохраненным продуктам, адресам доставки и профилю.</p>
                </div>
            </div>

            <div class="article-grid">
                <a href="{{ route('account.orders.index') }}" class="content-card content-card--link">
                    <h2>Мои заказы</h2>
                    <p>{{ auth()->user()->orders()->count() }} заказов в истории и быстрый повтор любой корзины.</p>
                </a>
                <a href="{{ route('account.favorites.index') }}" class="content-card content-card--link">
                    <h2>Избранное</h2>
                    <p>{{ auth()->user()->favoriteProducts()->count() }} сохраненных позиций для сезонных и повторных покупок.</p>
                </a>
                <a href="{{ route('account.addresses.index') }}" class="content-card content-card--link">
                    <h2>Адреса доставки</h2>
                    <p>{{ auth()->user()->addresses()->count() }} адресов в адресной книге для быстрого checkout.</p>
                </a>
                <a href="{{ route('profile.edit') }}" class="content-card content-card--link">
                    <h2>Профиль</h2>
                    <p>Редактирование имени, email, телефона и параметров доступа.</p>
                </a>
                @if (auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="content-card content-card--link">
                        <h2>Админ-панель</h2>
                        <p>Управление товарами, контентом, заказами и витриной магазина.</p>
                    </a>
                @endif
            </div>
        </div>
    </section>
@endsection
