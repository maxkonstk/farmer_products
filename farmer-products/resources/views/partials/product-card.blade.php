<article class="product-card">
    <a href="{{ route('products.show', $product) }}" class="product-card__image-wrap">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-card__image">
    </a>

    <div class="product-card__body">
        <div class="product-card__meta">
            <span>{{ $product->category->name }}</span>
            <span>{{ $product->weight ?: 'Уточняется' }}</span>
        </div>

        <h3 class="product-card__title">
            <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
        </h3>

        <p class="product-card__text">{{ \Illuminate\Support\Str::limit($product->description, 82) }}</p>

        <div class="product-card__journal-line">
            <span>Материал</span>
            <a href="{{ route('products.show', $product) }}">Открыть</a>
        </div>

        <div class="product-card__bottom">
            <div class="product-card__price-wrap">
                <div class="product-card__price">{{ number_format((float) $product->price, 0, ',', ' ') }} ₽</div>
                <div class="product-card__stock">{{ $product->stock > 0 ? "В наличии: {$product->stock}" : 'Нет в наличии' }}</div>
            </div>

            @if ($product->stock > 0)
                <form method="POST" action="{{ route('cart.store', $product) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">В корзину</button>
                </form>
            @else
                <button type="button" class="btn btn-disabled" disabled>Недоступно</button>
            @endif
        </div>
    </div>
</article>
