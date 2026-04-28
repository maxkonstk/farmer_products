@extends('layouts.site')

@section('title', 'Доставка и самовывоз')
@section('meta_description', 'Условия доставки и самовывоза: слоты, зоны обслуживания, стандарты сборки заказа и работа с заменой отсутствующих товаров.')

@section('content')
    <section class="page-section">
        <div class="site-container article-layout">
            <div class="page-intro">
                <div>
                    <p class="eyebrow">Доставка</p>
                    <h1 class="page-title">Как мы собираем и привозим заказы</h1>
                    <p class="page-subtitle">Для скоропортящихся категорий важны не только цены, но и время сборки, холодовая цепочка и прозрачность замены товаров. Поэтому финально подтверждаем заказ вручную, а не в безличном потоке.</p>
                </div>
            </div>

            <div class="article-grid">
                @foreach ($deliveryWindows as $window)
                    <article class="content-card">
                        <h2>{{ $window }}</h2>
                        <p>Слот можно выбрать при оформлении или согласовать с менеджером, если удобнее другой интервал.</p>
                    </article>
                @endforeach
            </div>

            <div class="detail-grid">
                <article class="content-card">
                    <h2>Зоны обслуживания</h2>
                    <ul class="info-list">
                        @foreach ($deliveryZones as $zone)
                            <li>{{ $zone }}</li>
                        @endforeach
                    </ul>
                </article>
                <article class="content-card">
                    <h2>Стандарты доставки</h2>
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
