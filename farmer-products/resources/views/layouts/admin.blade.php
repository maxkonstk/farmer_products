<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Админ-панель') - {{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $adminNavigation = [
            ['route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'label' => 'Обзор'],
            ['route' => 'admin.categories.index', 'active' => 'admin.categories.*', 'label' => 'Категории'],
            ['route' => 'admin.products.index', 'active' => 'admin.products.*', 'label' => 'Товары'],
            ['route' => 'admin.farmers.index', 'active' => 'admin.farmers.*', 'label' => 'Фермеры'],
            ['route' => 'admin.testimonials.index', 'active' => 'admin.testimonials.*', 'label' => 'Отзывы'],
            ['route' => 'admin.faq-items.index', 'active' => 'admin.faq-items.*', 'label' => 'FAQ'],
            ['route' => 'admin.orders.index', 'active' => 'admin.orders.*', 'label' => 'Заказы'],
        ];
    @endphp

    <body class="site-body admin-body">
        <div class="site-shell">
            <header class="site-header site-header--admin" x-data="{ mobileMenuOpen: false }">
                <div class="site-container issue-bar">
                    <span class="issue-bar__item">Админ-выпуск</span>
                    <span class="issue-bar__item">Каталог · остатки · заказы покупателей</span>
                    <span class="issue-bar__item">{{ auth()->user()->name }} · редактор смены</span>
                </div>

                <div class="site-container site-header__inner">
                    <a href="{{ route('admin.dashboard') }}" class="brand">
                        <span class="brand__mark">Ф</span>
                        <span>
                            <span class="brand__title">Фермерская лавка</span>
                            <span class="brand__subtitle">редакционная панель управления магазином</span>
                        </span>
                    </a>

                    <nav class="site-nav">
                        <a href="{{ route('home') }}" class="site-nav__link">Главная</a>
                        <a href="{{ route('catalog.index') }}" class="site-nav__link">Каталог</a>
                        <a href="{{ route('pages.about') }}" class="site-nav__link">О нас</a>
                        <a href="{{ route('pages.contacts') }}" class="site-nav__link">Контакты</a>
                    </nav>

                    <div class="site-actions">
                        <a href="{{ route('home') }}" class="btn btn-light">В магазин</a>
                        <a href="{{ route('account.orders.index') }}" class="btn btn-ghost">Мои заказы</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-ghost">Выйти</button>
                        </form>

                        <button type="button" class="mobile-menu-button" @click="mobileMenuOpen = ! mobileMenuOpen" aria-label="Открыть меню">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>

                <div class="site-container admin-strip" aria-label="Навигация по админ-панели">
                    @foreach ($adminNavigation as $item)
                        <a href="{{ route($item['route']) }}" class="admin-strip__link {{ request()->routeIs($item['active']) ? 'is-active' : '' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="mobile-menu" x-show="mobileMenuOpen" x-transition.origin.top @click.outside="mobileMenuOpen = false" x-cloak>
                    <a href="{{ route('home') }}" class="mobile-menu__link">Главная</a>
                    <a href="{{ route('catalog.index') }}" class="mobile-menu__link">Каталог</a>
                    <a href="{{ route('pages.about') }}" class="mobile-menu__link">О нас</a>
                    <a href="{{ route('pages.contacts') }}" class="mobile-menu__link">Контакты</a>
                    <a href="{{ route('account.orders.index') }}" class="mobile-menu__link">Мои заказы</a>
                    @foreach ($adminNavigation as $item)
                        <a href="{{ route($item['route']) }}" class="mobile-menu__link">{{ $item['label'] }}</a>
                    @endforeach
                </div>
            </header>

            @include('partials.flash')

            <main class="site-main">
                <section class="page-section admin-page-section">
                    <div class="site-container">
                        <div class="page-intro admin-page-intro">
                            <div>
                                <p class="eyebrow">@yield('page-eyebrow', 'Админ-панель')</p>
                                <h1 class="page-title">@yield('page-title', 'Панель управления')</h1>
                                <p class="page-subtitle">@yield('page-subtitle', 'Управление справочниками, товарами и заказами интернет-магазина в той же визуальной системе, что и витрина.')</p>
                            </div>

                            <div class="intro-note admin-intro-note">
                                <span>@yield('page-note-label', 'Оператор')</span>
                                <strong>@yield('page-note-title', auth()->user()->name)</strong>
                                <p class="admin-intro-note__text">@yield('page-note', 'Все административные разделы собраны в единый интерфейс без отдельной backend-оболочки, чтобы админка ощущалась продолжением основного сайта.')</p>
                            </div>
                        </div>

                        <div class="admin-content-stack">
                            @yield('content')
                        </div>
                    </div>
                </section>
            </main>

            <footer class="site-footer">
                <div class="site-container site-footer__grid">
                    <div>
                        <p class="site-footer__title">Фермерская лавка</p>
                        <p class="site-footer__text">Административная часть теперь оформлена в том же editorial-стиле, что и витрина магазина: общая типографика, карточная система и спокойная навигация.</p>
                    </div>
                    <div>
                        <p class="site-footer__title">Разделы панели</p>
                        <div class="site-footer__links">
                            @foreach ($adminNavigation as $item)
                                <a href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="site-footer__title">Оператор</p>
                        <div class="site-footer__links">
                            <span>{{ auth()->user()->name }}</span>
                            <span>{{ auth()->user()->email }}</span>
                            <a href="{{ route('home') }}">Открыть витрину</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
