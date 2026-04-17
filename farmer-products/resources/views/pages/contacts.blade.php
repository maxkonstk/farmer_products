@extends('layouts.site')

@section('title', 'Контакты')

@section('content')
    <section class="page-section">
        <div class="site-container">
            <div class="page-intro">
                <div>
                    <p class="eyebrow">Контактная информация</p>
                    <h1 class="page-title">Связаться с магазином</h1>
                    <p class="page-subtitle">Контакты представлены как часть учебного проекта и демонстрируют типовое наполнение страницы интернет-магазина.</p>
                </div>
            </div>

            <div class="article-grid">
                <article class="content-card">
                    <h2>Адрес</h2>
                    <p>г. Самара, ул. Садовая, 15</p>
                </article>
                <article class="content-card">
                    <h2>Телефон</h2>
                    <p>+7 (927) 000-24-24</p>
                </article>
                <article class="content-card">
                    <h2>Email</h2>
                    <p>hello@farm-lavka.local</p>
                </article>
                <article class="content-card">
                    <h2>График работы</h2>
                    <p>Пн–Сб: 09:00–20:00, Вс: 10:00–17:00</p>
                </article>
            </div>
        </div>
    </section>
@endsection
