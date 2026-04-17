@extends('layouts.site')

@section('title', 'Личный кабинет')

@section('content')
    <section class="page-section">
        <div class="site-container">
            <div class="page-intro">
                <div>
                    <p class="eyebrow">Личный кабинет</p>
                    <h1 class="page-title">Панель пользователя</h1>
                    <p class="page-subtitle">Отсюда можно перейти к истории заказов, профилю и административной панели при наличии прав доступа.</p>
                </div>
            </div>

            <div class="article-grid">
                <a href="{{ route('account.orders.index') }}" class="content-card content-card--link">
                    <h2>Мои заказы</h2>
                    <p>Просмотр списка заказов и состава каждой покупки.</p>
                </a>
                <a href="{{ route('profile.edit') }}" class="content-card content-card--link">
                    <h2>Профиль</h2>
                    <p>Редактирование имени, email и пароля учетной записи.</p>
                </a>
                @if (auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="content-card content-card--link">
                        <h2>Админ-панель</h2>
                        <p>Управление категориями, товарами и заказами магазина.</p>
                    </a>
                @endif
            </div>
        </div>
    </section>
@endsection
