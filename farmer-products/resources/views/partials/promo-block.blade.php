@php
    $variant = $variant ?? 'grid';
    $targetLabel = $promoBlock->target_label;
    $imageAttributes = \App\Support\ImageMetadata::attributes($promoBlock->image_url, 1200, 860);
@endphp

<article class="promo-card promo-card--{{ $variant }} promo-card--{{ $promoBlock->theme }}">
    <div class="promo-card__media">
        <img
            src="{{ $promoBlock->image_url }}"
            alt="{{ $promoBlock->title }}"
            loading="lazy"
            decoding="async"
            width="{{ $imageAttributes['width'] }}"
            height="{{ $imageAttributes['height'] }}"
            sizes="{{ $variant === 'inline' ? '(max-width: 900px) 100vw, 34vw' : '(max-width: 640px) 100vw, (max-width: 1100px) 50vw, 33vw' }}"
        >
    </div>

    <div class="promo-card__content">
        <div class="promo-card__meta">
            @if ($promoBlock->eyebrow)
                <p class="eyebrow">{{ $promoBlock->eyebrow }}</p>
            @endif
            @if ($promoBlock->badge)
                <span class="product-badge">{{ $promoBlock->badge }}</span>
            @endif
        </div>

        <h3>{{ $promoBlock->title }}</h3>
        <p>{{ $promoBlock->body }}</p>

        @if ($targetLabel)
            <span class="promo-card__target">{{ $targetLabel }}</span>
        @endif

        <div class="promo-card__actions">
            <a href="{{ $promoBlock->resolved_url }}" class="btn {{ $promoBlock->theme === 'charcoal' ? 'btn-light' : 'btn-primary' }}">
                {{ $promoBlock->resolved_cta_label }}
            </a>
        </div>
    </div>
</article>
