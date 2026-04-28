@extends('layouts.site')

@section('title', 'Контакты')
@section('meta_description', 'Контакты фермерской лавки: адрес, часы работы, телефон, email и зоны доставки по Самаре.')

@section('content')
    @php($brand = config('shop.brand'))
    <section class="page-section">
        <div class="site-container">
            <div class="page-intro">
                <div>
                    <p class="eyebrow">Контакты</p>
                    <h1 class="page-title">Связаться с лавкой</h1>
                    <p class="page-subtitle">Если хотите уточнить состав партии, слот доставки, замену товара или самовывоз, удобнее всего написать или позвонить до оформления заказа.</p>
                </div>
            </div>

            <div class="article-grid">
                <article class="content-card">
                    <h2>Адрес</h2>
                    <p>{{ $brand['address'] }}</p>
                </article>
                <article class="content-card">
                    <h2>Телефон</h2>
                    <p>{{ $brand['phone'] }}</p>
                </article>
                <article class="content-card">
                    <h2>Email</h2>
                    <p>{{ $brand['email'] }}</p>
                </article>
                <article class="content-card">
                    <h2>Часы работы</h2>
                    @foreach ($brand['hours'] as $hoursRow)
                        <p>{{ $hoursRow }}</p>
                    @endforeach
                </article>
            </div>

            <div class="detail-grid">
                <article class="content-card">
                    <h2>Где доставляем</h2>
                    <ul class="info-list">
                        @foreach ($deliveryZones as $zone)
                            <li>{{ $zone }}</li>
                        @endforeach
                    </ul>
                </article>
                <article class="content-card">
                    <h2>Что важно знать заранее</h2>
                    <ul class="info-list">
                        @foreach ($deliveryPromises as $promise)
                            <li>{{ $promise }}</li>
                        @endforeach
                    </ul>
                </article>
            </div>
        </div>
    </section>
@endsection
