import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.dataLayer = window.dataLayer || [];
window.appAnalytics = {
    push(event) {
        if (! event || typeof event !== 'object') {
            return;
        }

        window.dataLayer.push(event);
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

Alpine.start();
