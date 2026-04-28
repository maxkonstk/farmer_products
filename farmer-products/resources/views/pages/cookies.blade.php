@extends('layouts.site')

@section('title', 'Политика использования cookies')
@section('meta_description', 'Какие cookies использует фермерская лавка для работы корзины, авторизации, аналитики и оценки Core Web Vitals.')

@section('content')
    <section class="page-section">
        <div class="site-container article-layout">
            <div class="page-intro">
                <div>
                    <p class="eyebrow">Cookies</p>
                    <h1 class="page-title">Как мы используем cookies</h1>
                    <p class="page-subtitle">Без технических cookies магазин не сможет хранить корзину, вход в кабинет и защиту форм. Аналитические cookies подключаются отдельно, только если вы их разрешили.</p>
                </div>
            </div>

            <div class="article-grid">
                <article class="content-card">
                    <h2>Необходимые cookies</h2>
                    <p>Поддерживают сессию, корзину, авторизацию, CSRF-защиту, flash-сообщения и сохранение состояния checkout. Без них storefront перестает быть функциональным.</p>
                </article>
                <article class="content-card">
                    <h2>Аналитические cookies</h2>
                    <p>Используются только при вашем согласии. Они помогают понять, как работают поиск, фильтры, карточки товара, checkout и какие страницы теряют конверсию.</p>
                </article>
                <article class="content-card">
                    <h2>Текущий analytics sink</h2>
                    <p>
                        @if (($analytics['provider'] ?? 'none') === 'ga4')
                            Сейчас доступна интеграция с Google Analytics 4.
                        @elseif (($analytics['provider'] ?? 'none') === 'gtm')
                            Сейчас доступна интеграция через Google Tag Manager.
                        @else
                            Отдельный внешний analytics sink сейчас не подключен.
                        @endif
                    </p>
                </article>
            </div>

            <div class="detail-grid">
                <article class="content-card">
                    <h2>Как изменить выбор</h2>
                    <p>Внизу сайта доступна ссылка «Настроить cookies». Вы также можете очистить cookies в браузере и заново выбрать режим при следующем посещении.</p>
                </article>
                <article class="content-card">
                    <h2>Что попадает в аналитику</h2>
                    <p>События просмотра товара, работы каталога, поиска, фильтров, корзины, checkout и web vitals. Мы не используем эти данные для построения рекламных профилей внутри магазина.</p>
                </article>
            </div>
        </div>
    </section>
@endsection
