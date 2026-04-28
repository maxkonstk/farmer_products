<div class="cookie-consent {{ $cookieConsentState === 'unknown' ? '' : 'is-hidden' }}" data-cookie-consent-banner role="dialog" aria-live="polite" aria-label="Настройки cookies">
    <div class="cookie-consent__content">
        <div>
            <p class="eyebrow">Cookies и аналитика</p>
            <h2>Разрешить аналитические cookies?</h2>
            <p>Используем cookies для корзины, входа и работы магазина. Отдельно можно разрешить аналитику, чтобы видеть поиск, фильтры и web vitals без персональных данных.</p>
            <p><a href="{{ route('pages.cookies') }}">Подробнее о cookies</a> и <a href="{{ route('pages.privacy') }}">обработке данных</a>.</p>
        </div>
        <div class="cookie-consent__actions">
            <button type="button" class="btn btn-primary" data-cookie-consent-action="accept">Разрешить аналитику</button>
            <button type="button" class="btn btn-outline" data-cookie-consent-action="reject">Только необходимые</button>
        </div>
    </div>
</div>
