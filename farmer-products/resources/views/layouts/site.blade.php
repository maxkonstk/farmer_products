<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @php
        $brand = $shopBrand ?? config('shop.brand');
        $delivery = $shopDelivery ?? config('shop.delivery');
        $metaDescription = trim((string) $__env->yieldContent('meta_description', $brand['tagline']));
        $metaTitle = trim((string) $__env->yieldContent('title', config('app.name')));
        $defaultRobots = request()->routeIs('cart.*', 'checkout.*', 'dashboard', 'account.*', 'profile.*') ? 'noindex,nofollow' : 'index,follow';
        $metaRobots = trim((string) $__env->yieldContent('meta_robots', $defaultRobots));
        $canonicalUrl = trim((string) $__env->yieldContent('meta_canonical', url()->current()));
        $metaImage = trim((string) $__env->yieldContent('meta_image', asset('images/products/hero-farm.svg')));
        $analytics = $shopAnalytics ?? config('shop.analytics', []);
        $analyticsProvider = $analytics['provider'] ?? 'none';
        $gaMeasurementId = trim((string) ($analytics['ga_measurement_id'] ?? ''));
        $gtmContainerId = trim((string) ($analytics['gtm_container_id'] ?? ''));
        $trackWebVitals = (bool) ($analytics['track_web_vitals'] ?? true);
        $analyticsDebug = (bool) ($analytics['debug_mode'] ?? false);
        $analyticsRequiresConsent = (bool) ($analytics['requires_consent'] ?? true) && in_array($analyticsProvider, ['ga4', 'gtm'], true);
        $cookieConsentVersion = trim((string) ($analytics['consent_version'] ?? '2026-04'));
        $cookieConsentRaw = trim((string) request()->cookie('shop_cookie_consent', ''));
        [$cookieConsentValue, $cookieConsentCookieVersion] = array_pad(explode(':', $cookieConsentRaw, 2), 2, null);
        $cookieConsentState = $cookieConsentCookieVersion === $cookieConsentVersion && in_array($cookieConsentValue, ['accepted', 'rejected'], true)
            ? $cookieConsentValue
            : 'unknown';
        $analyticsEnabled = ! $analyticsRequiresConsent || $cookieConsentState === 'accepted';
    @endphp
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $metaTitle }}</title>
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="robots" content="{{ $metaRobots }}">
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

        <script>
            window.dataLayer = window.dataLayer || [];
        </script>
        @if ($analyticsEnabled && $analyticsProvider === 'ga4' && $gaMeasurementId !== '')
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaMeasurementId }}"></script>
            <script>
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ $gaMeasurementId }}', {
                    page_title: @js($metaTitle),
                    page_location: @js($canonicalUrl),
                    page_path: @js('/'.ltrim(request()->path(), '/')),
                    @if ($analyticsDebug)
                        debug_mode: true,
                    @endif
                });
            </script>
        @elseif ($analyticsEnabled && $analyticsProvider === 'gtm' && $gtmContainerId !== '')
            <script>
                (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer','{{ $gtmContainerId }}');
            </script>
        @endif

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        @stack('head')

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('structured_data')
    </head>
    <body
        class="site-body"
        data-analytics-provider="{{ $analyticsProvider }}"
        data-analytics-web-vitals="{{ $trackWebVitals ? 'true' : 'false' }}"
        data-analytics-debug="{{ $analyticsDebug ? 'true' : 'false' }}"
        data-analytics-ga-id="{{ $gaMeasurementId }}"
        data-analytics-gtm-id="{{ $gtmContainerId }}"
        data-analytics-loaded="{{ $analyticsEnabled ? 'true' : 'false' }}"
        data-analytics-consent-required="{{ $analyticsRequiresConsent ? 'true' : 'false' }}"
        data-cookie-consent-state="{{ $cookieConsentState }}"
        data-cookie-consent-version="{{ $cookieConsentVersion }}"
    >
        @if ($analyticsEnabled && $analyticsProvider === 'gtm' && $gtmContainerId !== '')
            <noscript>
                <iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmContainerId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe>
            </noscript>
        @endif
        <a href="#main-content" class="skip-link">Перейти к содержимому</a>
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

                    <nav class="site-nav" aria-label="Основная навигация">
                        <a href="{{ route('home') }}" class="site-nav__link {{ request()->routeIs('home') ? 'is-active' : '' }}" @if (request()->routeIs('home')) aria-current="page" @endif>Главная</a>
                        <a href="{{ route('catalog.index') }}" class="site-nav__link {{ request()->routeIs('catalog.*', 'categories.show', 'products.show') ? 'is-active' : '' }}" @if (request()->routeIs('catalog.*', 'categories.show', 'products.show')) aria-current="page" @endif>Каталог</a>
                        <a href="{{ route('pages.about') }}" class="site-nav__link {{ request()->routeIs('pages.about') ? 'is-active' : '' }}" @if (request()->routeIs('pages.about')) aria-current="page" @endif>О нас</a>
                        <a href="{{ route('pages.contacts') }}" class="site-nav__link {{ request()->routeIs('pages.contacts') ? 'is-active' : '' }}" @if (request()->routeIs('pages.contacts')) aria-current="page" @endif>Контакты</a>
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

                        <button
                            type="button"
                            class="mobile-menu-button"
                            @click="mobileMenuOpen = ! mobileMenuOpen"
                            aria-controls="site-mobile-menu"
                            :aria-expanded="mobileMenuOpen.toString()"
                            :aria-label="mobileMenuOpen ? 'Закрыть меню' : 'Открыть меню'"
                        >
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>

                @if ($navigationCategories->isNotEmpty())
                    <div class="site-container category-strip" aria-label="Категории каталога">
                        @foreach ($navigationCategories as $navCategory)
                            <a href="{{ route('categories.show', $navCategory['slug']) }}" class="category-pill {{ request()->routeIs('categories.show') && request()->route('category')?->slug === $navCategory['slug'] ? 'is-active' : '' }}">
                                {{ $navCategory['name'] }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div id="site-mobile-menu" class="mobile-menu" x-show="mobileMenuOpen" x-transition.origin.top @click.outside="mobileMenuOpen = false" x-cloak>
                    <a href="{{ route('home') }}" class="mobile-menu__link" @click="mobileMenuOpen = false">Главная</a>
                    <a href="{{ route('catalog.index') }}" class="mobile-menu__link" @click="mobileMenuOpen = false">Каталог</a>
                    <a href="{{ route('pages.about') }}" class="mobile-menu__link" @click="mobileMenuOpen = false">О нас</a>
                    <a href="{{ route('pages.contacts') }}" class="mobile-menu__link" @click="mobileMenuOpen = false">Контакты</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="mobile-menu__link" @click="mobileMenuOpen = false">Кабинет</a>
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="mobile-menu__link" @click="mobileMenuOpen = false">Админ-панель</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="mobile-menu__link" @click="mobileMenuOpen = false">Войти</a>
                        <a href="{{ route('register') }}" class="mobile-menu__link" @click="mobileMenuOpen = false">Регистрация</a>
                    @endauth
                </div>
            </header>

            @include('partials.flash')

            <main id="main-content" class="site-main" tabindex="-1">
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
                        <p class="site-footer__title">Документы</p>
                        <div class="site-footer__links">
                            <a href="{{ route('pages.privacy') }}">Конфиденциальность</a>
                            <a href="{{ route('pages.cookies') }}">Cookies</a>
                            <a href="{{ route('pages.terms') }}">Условия заказа</a>
                            @if ($analyticsRequiresConsent)
                                <button type="button" class="footer-link-button" data-cookie-preferences-trigger>Настроить cookies</button>
                            @endif
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

        @if ($analyticsRequiresConsent)
            @include('partials.cookie-consent', ['cookieConsentState' => $cookieConsentState])
        @endif

        @foreach (($analyticsInitialEvents ?? []) as $analyticsEvent)
            @include('partials.analytics-event', ['event' => $analyticsEvent])
        @endforeach
        @stack('analytics_events')
    </body>
</html>
