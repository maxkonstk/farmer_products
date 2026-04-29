@extends('layouts.site')

@section('title', 'О фермерской лавке')
@section('meta_description', 'Кто стоит за проектом «Фермерская лавка»: локальные хозяйства, короткая цепочка поставки и принципы работы с сезонными продуктами в Ижевске.')

@section('content')
    <section class="page-section">
        <div class="site-container article-layout">
            <div class="page-intro">
                <div>
                    <p class="eyebrow">О магазине</p>
                    <h1 class="page-title">Небольшой локальный магазин с короткой цепочкой поставки</h1>
                    <p class="page-subtitle">Мы собираем сезонные продукты у небольших хозяйств, привозим их в Ижевск без длинного складского плеча и сохраняем живой контакт с покупателем на каждом заказе.</p>
                </div>
            </div>

            <div class="article-grid">
                <article class="content-card">
                    <h2>Что мы продаем</h2>
                    <p>Овощи, зелень, молочку, сыры, мясо, мед, выпечку и заготовки для повседневной корзины. В каждом разделе стараемся держать не бесконечный каталог, а понятный ассортимент, который можно действительно заказать на ближайшую неделю.</p>
                </article>
                <article class="content-card">
                    <h2>Как работаем с поставщиками</h2>
                    <p>Предпочитаем небольшие хозяйства и ремесленные производства, где можно подтвердить происхождение партии, сроки поставки и условия хранения. Для скоропортящихся категорий выстраиваем поставку под конкретные дни отгрузки, а не под длительный склад.</p>
                </article>
                <article class="content-card">
                    <h2>Что обещаем покупателю</h2>
                    <ul class="info-list">
                        @foreach ($promises as $promise)
                            <li>{{ $promise }}</li>
                        @endforeach
                    </ul>
                </article>
            </div>

            <div class="farm-grid">
                @foreach ($farmers as $farmer)
                    @php($imageAttributes = \App\Support\ImageMetadata::attributes($farmer['image_url'] ?? $farmer['image']))
                    <article class="farm-card">
                        <img
                            src="{{ $farmer['image_url'] ?? $farmer['image'] }}"
                            alt="{{ $farmer['name'] }}"
                            class="farm-card__image"
                            width="{{ $imageAttributes['width'] }}"
                            height="{{ $imageAttributes['height'] }}"
                            loading="lazy"
                            decoding="async"
                            sizes="(max-width: 640px) 100vw, (max-width: 1100px) 50vw, 33vw"
                        >
                        <div>
                            <p class="farm-card__eyebrow">{{ $farmer['location'] }}</p>
                            <h3>{{ $farmer['name'] }}</h3>
                            <p class="farm-card__specialty">{{ $farmer['specialty'] }}</p>
                            <p>{{ $farmer['story'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
