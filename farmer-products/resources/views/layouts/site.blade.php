<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @php
        $brand = config('shop.brand');
        $delivery = config('shop.delivery');
        $metaDescription = trim((string) $__env->yieldContent('meta_description', $brand['tagline']));
        $metaTitle = trim((string) $__env->yieldContent('title', config('app.name')));
        $canonicalUrl = url()->current();
        $metaImage = trim((string) $__env->yieldContent('meta_image', asset('images/products/hero-farm.svg')));
    @endphp
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $metaTitle }}</title>
        <meta name="description" content="{{ $metaDescription }}">
        <link rel="canonical" href="{{ $canonicalUrl }}">
        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:type" content="@yield('og_type', 'website')">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        <meta property="og:image" content="{{ $metaImage }}">
        <meta property="og:locale" content="ru_RU">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $metaTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        <meta name="twitter:image" content="{{ $metaImage }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('structured_data')
    </head>
    <body class="site-body">
        <div class="site-shell">
            <header class="site-header" x-data="{ mobileMenuOpen: false }">
                <div class="site-container issue-bar">
                    <span class="issue-bar__item">Самара · локальные хозяйства</span>
                    <span class="issue-bar__item">{{ $delivery['cutoff'] }}</span>
                    <span class="issue-bar__item">Доставка и самовывоз без маркетплейсного шума</span>
                </div>

                <div class="site-container site-header__inner">
                    <a href="{{ route('home') }}" class="brand">
                        <span class="brand__mark">Ф</span>
                        <span>
                            <span class="brand__title">{{ $brand['name'] }}</span>
                            <span class="brand__subtitle">{{ $brand['tagline'] }}</span>
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

                            <a href="{{ route('dashboard') }}" class="btn btn-ghost">Кабинет</a>

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
                        <a href="{{ route('dashboard') }}" class="mobile-menu__link">Кабинет</a>
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
                        <p class="site-footer__title">{{ $brand['name'] }}</p>
                        <p class="site-footer__text">{{ $brand['tagline'] }}</p>
                    </div>
                    <div>
                        <p class="site-footer__title">Покупателям</p>
                        <div class="site-footer__links">
                            <a href="{{ route('catalog.index') }}">Каталог</a>
                            <a href="{{ route('pages.delivery') }}">Доставка</a>
                            <a href="{{ route('pages.payment') }}">Оплата</a>
                            <a href="{{ route('pages.faq') }}">FAQ</a>
                        </div>
                    </div>
                    <div>
                        <p class="site-footer__title">Контакты</p>
                        <div class="site-footer__links">
                            <span>{{ $brand['address'] }}</span>
                            <span>{{ $brand['phone'] }}</span>
                            <span>{{ $brand['email'] }}</span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
