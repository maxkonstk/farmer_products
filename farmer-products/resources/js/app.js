import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.dataLayer = window.dataLayer || [];

const analyticsConfig = {
    provider: document.body?.dataset.analyticsProvider || 'none',
    gaMeasurementId: document.body?.dataset.analyticsGaId || '',
    gtmContainerId: document.body?.dataset.analyticsGtmId || '',
    loaded: document.body?.dataset.analyticsLoaded === 'true',
    trackWebVitals: document.body?.dataset.analyticsWebVitals === 'true',
    consentRequired: document.body?.dataset.analyticsConsentRequired === 'true',
    consentState: document.body?.dataset.cookieConsentState || 'unknown',
    consentVersion: document.body?.dataset.cookieConsentVersion || '2026-04',
};

function analyticsConsentGranted() {
    return ! analyticsConfig.consentRequired || analyticsConfig.consentState === 'accepted';
}

function normalizeGaPayload(event) {
    const payload = { ...event };

    delete payload.event;

    return payload;
}

function sendToGa4(event) {
    if (
        analyticsConfig.provider !== 'ga4'
        || ! analyticsConfig.loaded
        || ! analyticsConsentGranted()
        || typeof window.gtag !== 'function'
        || typeof event.event !== 'string'
    ) {
        return;
    }

    window.gtag('event', event.event, normalizeGaPayload(event));
}

window.appAnalytics = {
    push(event) {
        if (! event || typeof event !== 'object') {
            return;
        }

        window.dataLayer.push(event);
        sendToGa4(event);
        document.dispatchEvent(new CustomEvent('app:analytics', { detail: event }));
    },
    pushMany(events) {
        if (! Array.isArray(events)) {
            return;
        }

        events.forEach((event) => this.push(event));
    },
};

document.querySelectorAll('script[data-analytics-event]').forEach((node) => {
    try {
        const payload = JSON.parse(node.textContent || '{}');
        window.appAnalytics.push(payload);
    } catch (error) {
        console.warn('Unable to parse analytics payload.', error);
    }
});

function compact(payload) {
    return Object.fromEntries(
        Object.entries(payload).filter(([, value]) => value !== null && value !== undefined && value !== '' && (! Array.isArray(value) || value.length > 0)),
    );
}

function catalogFiltersFromForm(form) {
    const formData = new FormData(form);
    const rawFilters = {
        category: (formData.get('category') || '').toString().trim(),
        collection: (formData.get('collection') || '').toString().trim(),
        season: (formData.get('season') || '').toString().trim(),
        availability: (formData.get('availability') || '').toString().trim(),
        sort: (formData.get('sort') || '').toString().trim(),
    };

    const filters = Object.entries(rawFilters)
        .filter(([key, value]) => value !== '' && ! (key === 'sort' && value === 'latest'))
        .map(([key, value]) => `${key}:${value}`);

    return {
        searchTerm: (formData.get('q') || '').toString().trim(),
        sort: rawFilters.sort || 'latest',
        filters,
    };
}

function initCatalogAnalytics() {
    document.querySelectorAll('form[data-analytics-catalog-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const { searchTerm, sort, filters } = catalogFiltersFromForm(form);

            if (searchTerm !== '') {
                window.appAnalytics.push({
                    event: 'search',
                    search_term: searchTerm,
                    search_context: 'catalog',
                });
            }

            if (filters.length > 0 || sort !== 'latest') {
                window.appAnalytics.push(compact({
                    event: 'catalog_filters_applied',
                    filter_count: filters.length,
                    filters,
                    sort_option: sort !== 'latest' ? sort : null,
                }));
            }
        });
    });
}

function setCookieConsent(choice) {
    document.cookie = `shop_cookie_consent=${choice}:${analyticsConfig.consentVersion}; Max-Age=31536000; Path=/; SameSite=Lax`;
}

function initCookieConsent() {
    const banner = document.querySelector('[data-cookie-consent-banner]');

    if (! banner) {
        return;
    }

    document.querySelectorAll('[data-cookie-consent-action]').forEach((button) => {
        button.addEventListener('click', () => {
            const choice = button.getAttribute('data-cookie-consent-action');

            if (choice !== 'accept' && choice !== 'reject') {
                return;
            }

            setCookieConsent(choice);
            window.location.reload();
        });
    });

    document.querySelectorAll('[data-cookie-preferences-trigger]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            banner.classList.remove('is-hidden');
            banner.scrollIntoView({ behavior: 'smooth', block: 'end' });
        });
    });
}

function ratingForMetric(name, value) {
    if (name === 'CLS') {
        if (value <= 0.1) {
            return 'good';
        }

        if (value <= 0.25) {
            return 'needs-improvement';
        }

        return 'poor';
    }

    if (name === 'LCP') {
        if (value <= 2500) {
            return 'good';
        }

        if (value <= 4000) {
            return 'needs-improvement';
        }

        return 'poor';
    }

    if (name === 'FCP') {
        if (value <= 1800) {
            return 'good';
        }

        if (value <= 3000) {
            return 'needs-improvement';
        }

        return 'poor';
    }

    if (name === 'TTFB') {
        if (value <= 800) {
            return 'good';
        }

        if (value <= 1800) {
            return 'needs-improvement';
        }

        return 'poor';
    }

    return 'unknown';
}

function pushVitalMetric(name, value) {
    if (! Number.isFinite(value)) {
        return;
    }

    window.appAnalytics.push({
        event: 'web_vital',
        metric_name: name,
        metric_value: name === 'CLS' ? Number(value.toFixed(4)) : Math.round(value),
        metric_rating: ratingForMetric(name, value),
        page_path: window.location.pathname,
    });
}

function initWebVitals() {
    if (! analyticsConfig.trackWebVitals || typeof PerformanceObserver === 'undefined') {
        return;
    }

    const emittedMetrics = new Set();
    let lcpValue = null;
    let clsValue = 0;

    const emitOnce = (name, value) => {
        const key = `${name}:${window.location.pathname}`;

        if (emittedMetrics.has(key)) {
            return;
        }

        emittedMetrics.add(key);
        pushVitalMetric(name, value);
    };

    try {
        const navigationEntry = performance.getEntriesByType('navigation')[0];

        if (navigationEntry?.responseStart) {
            emitOnce('TTFB', navigationEntry.responseStart);
        }
    } catch (error) {
        console.warn('Unable to collect TTFB.', error);
    }

    try {
        const paintObserver = new PerformanceObserver((entryList) => {
            entryList.getEntries().forEach((entry) => {
                if (entry.name === 'first-contentful-paint') {
                    emitOnce('FCP', entry.startTime);
                }
            });
        });

        paintObserver.observe({ type: 'paint', buffered: true });
    } catch (error) {
        console.warn('Unable to observe paint metrics.', error);
    }

    try {
        const lcpObserver = new PerformanceObserver((entryList) => {
            const entries = entryList.getEntries();
            const lastEntry = entries[entries.length - 1];

            if (lastEntry) {
                lcpValue = lastEntry.startTime;
            }
        });

        lcpObserver.observe({ type: 'largest-contentful-paint', buffered: true });
    } catch (error) {
        console.warn('Unable to observe LCP.', error);
    }

    try {
        const clsObserver = new PerformanceObserver((entryList) => {
            entryList.getEntries().forEach((entry) => {
                if (! entry.hadRecentInput) {
                    clsValue += entry.value;
                }
            });
        });

        clsObserver.observe({ type: 'layout-shift', buffered: true });
    } catch (error) {
        console.warn('Unable to observe CLS.', error);
    }

    const flushPageMetrics = () => {
        if (lcpValue !== null) {
            emitOnce('LCP', lcpValue);
        }

        emitOnce('CLS', clsValue);
    };

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden') {
            flushPageMetrics();
        }
    });

    window.addEventListener('pagehide', flushPageMetrics, { once: true });
}

initCatalogAnalytics();
initCookieConsent();
initWebVitals();

Alpine.start();
