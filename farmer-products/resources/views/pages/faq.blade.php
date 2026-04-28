@extends('layouts.site')

@section('title', 'FAQ')
@section('meta_description', 'Ответы на частые вопросы о фермерских продуктах, доставке, замене товаров, самовывозе и первом заказе.')

@push('structured_data')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqItems)->map(fn (array $faqItem) => [
                '@type' => 'Question',
                'name' => $faqItem['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faqItem['answer'],
                ],
            ])->all(),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <section class="page-section">
        <div class="site-container article-layout">
            <div class="page-intro">
                <div>
                    <p class="eyebrow">FAQ</p>
                    <h1 class="page-title">Ответы на частые вопросы</h1>
                    <p class="page-subtitle">Собрали то, что чаще всего спрашивают перед первым заказом: происхождение товаров, замены, самовывоз и окна доставки.</p>
                </div>
            </div>

            <div class="faq-list">
                @foreach ($faqItems as $faqItem)
                    <details class="faq-item" @if ($loop->first) open @endif>
                        <summary>{{ $faqItem['question'] }}</summary>
                        <div class="faq-answer">
                            <p>{{ $faqItem['answer'] }}</p>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
@endsection
