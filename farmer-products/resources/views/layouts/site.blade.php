<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name'))</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="site-body">
        <div class="site-shell">
            <header class="site-header" x-data="{ mobileMenuOpen: false }">
                <div class="site-container issue-bar">
                    <span class="issue-bar__item">Выпуск 04</span>
                    <span class="issue-bar__item">Журнал сезонных продуктов</span>
                    <span class="issue-bar__item">Самара · локальные хозяйства · доставка</span>
                </div>

                <div class="site-container site-header__inner">
                    <a href="{{ route('home') }}" class="brand">
                        <span class="brand__mark">Ф</span>
                        <span>
                            <span class="brand__title">Фермерская лавка</span>
                            <span class="brand__subtitle">журнал свежих продуктов и покупок</span>
                        </span>
                    </a>

                    <nav class="site-nav">
                        <a href="{{ route('home') }}" class="site-nav__link {{ request()->routeIs('home') ? 'is-active' : '' }}">Главная</a>
                        <a href="{{ route('catalog.index') }}" class="site-nav__link {{ request()->routeIs('catalog.*', 'categories.show', 'products.show') ? 'is-active' : '' }}">Каталог</a>
                        <a href="{{ route('pages.about') }}" class="site-nav__link {{ request()->routeIs('pages.about') ? 'is-active' : '' }}">О нас</a>
                        <a href="{{ route('pages.contacts') }}" class="site-nav__link {{ request()->routeIs('pages.contacts') ? 'is-active' : '' }}">Контакты</a>
                    </nav>

                    <div class="site-actions">
                        <a href="{{ route('cart.index') }}" class="cart-button {{ request()->routeIs('cart.*', 'checkout.*') ? 'is-active' : '' }}">
                            <span>Корзина</span>
                            <span class="cart-button__count">{{ $cartItemsCount }}</span>
                        </a>

                        @auth
                            @if (auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Админ-панель</a>
                            @endif

                            <a href="{{ route('account.orders.index') }}" class="btn btn-ghost">Мои заказы</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-ghost">Выйти</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-ghost">Войти</a>
                            <a href="{{ route('register') }}" class="btn btn-light">Регистрация</a>
                        @endauth

                        <button type="button" class="mobile-menu-button" @click="mobileMenuOpen = ! mobileMenuOpen" aria-label="Открыть меню">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>

                @if ($navigationCategories->isNotEmpty())
                    <div class="site-container category-strip">
                        @foreach ($navigationCategories as $navCategory)
                            <a href="{{ route('categories.show', $navCategory['slug']) }}" class="category-pill {{ request()->routeIs('categories.show') && request()->route('category')?->slug === $navCategory['slug'] ? 'is-active' : '' }}">
                                {{ $navCategory['name'] }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="mobile-menu" x-show="mobileMenuOpen" x-transition.origin.top @click.outside="mobileMenuOpen = false" x-cloak>
                    <a href="{{ route('home') }}" class="mobile-menu__link">Главная</a>
                    <a href="{{ route('catalog.index') }}" class="mobile-menu__link">Каталог</a>
                    <a href="{{ route('pages.about') }}" class="mobile-menu__link">О нас</a>
                    <a href="{{ route('pages.contacts') }}" class="mobile-menu__link">Контакты</a>
                    @auth
                        <a href="{{ route('account.orders.index') }}" class="mobile-menu__link">Мои заказы</a>
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="mobile-menu__link">Админ-панель</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="mobile-menu__link">Войти</a>
                        <a href="{{ route('register') }}" class="mobile-menu__link">Регистрация</a>
                    @endauth
                </div>
            </header>

            @include('partials.flash')

            <main class="site-main">
                @yield('content')
            </main>

            <footer class="site-footer">
                <div class="site-container site-footer__grid">
                    <div>
                        <p class="site-footer__title">Фермерская лавка</p>
                        <p class="site-footer__text">Редакционный storefront о сезонных фермерских продуктах: журнал, каталог, корзина, оформление заказа и административная часть в одном проекте.</p>
                    </div>
                    <div>
                        <p class="site-footer__title">Рубрики</p>
                        <div class="site-footer__links">
                            <a href="{{ route('catalog.index') }}">Каталог</a>
                            <a href="{{ route('pages.about') }}">О магазине</a>
                            <a href="{{ route('pages.contacts') }}">Контакты</a>
                        </div>
                    </div>
                    <div>
                        <p class="site-footer__title">Редакция</p>
                        <div class="site-footer__links">
                            <span>г. Самара, ул. Садовая, 15</span>
                            <span>+7 (927) 000-24-24</span>
                            <span>hello@farm-lavka.local</span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
