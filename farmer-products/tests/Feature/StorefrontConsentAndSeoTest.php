<?php

namespace Tests\Feature;

use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontConsentAndSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_analytics_scripts_wait_for_cookie_consent_and_legal_pages_are_available(): void
    {
        config()->set('shop.analytics.provider', 'gtm');
        config()->set('shop.analytics.gtm_container_id', 'GTM-TEST123');
        config()->set('shop.analytics.requires_consent', true);
        config()->set('shop.analytics.consent_version', 'test-v1');

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Разрешить аналитические cookies?', false);
        $response->assertSee('data-cookie-consent-state="unknown"', false);
        $response->assertDontSee('googletagmanager.com/gtm.js?id=GTM-TEST123', false);

        $acceptedResponse = $this->withCookie('shop_cookie_consent', 'accepted:test-v1')->get(route('home'));

        $acceptedResponse->assertOk();
        $acceptedResponse->assertSee('googletagmanager.com/gtm.js?id=', false);
        $acceptedResponse->assertSee('GTM-TEST123', false);
        $acceptedResponse->assertSee('data-analytics-loaded="true"', false);

        $this->get(route('pages.privacy'))->assertOk()->assertSee('Политика конфиденциальности');
        $this->get(route('pages.cookies'))->assertOk()->assertSee('Как мы используем cookies');
        $this->get(route('pages.terms'))->assertOk()->assertSee('Условия оформления и подтверждения заказа');
    }

    public function test_filtered_catalog_is_noindex_and_robots_txt_is_available(): void
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $response = $this->get(route('catalog.index', [
            'q' => 'молоко',
            'availability' => 'in-stock',
        ]));

        $response->assertOk();
        $response->assertSee('<meta name="robots" content="noindex,follow">', false);
        $response->assertSee('<link rel="canonical" href="'.route('catalog.index').'">', false);

        $robotsResponse = $this->get(route('robots'));

        $robotsResponse->assertOk();
        $robotsResponse->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $robotsResponse->assertSee('Disallow: /checkout');
        $robotsResponse->assertSee('Disallow: /account');
        $robotsResponse->assertSee('Sitemap: '.route('sitemap'));
    }
}
