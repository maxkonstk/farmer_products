<?php

namespace Tests\Feature;

use App\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Tests\TestCase;

class TrustedProxyHttpsAssetsTest extends TestCase
{
    public function test_trusted_proxy_marks_asset_urls_as_https(): void
    {
        $request = Request::create(
            'http://farmerproducts-production.up.railway.app/login',
            'GET',
            server: [
                'REMOTE_ADDR' => '10.0.0.1',
                'HTTP_HOST' => 'farmerproducts-production.up.railway.app',
                'HTTP_X_FORWARDED_HOST' => 'farmerproducts-production.up.railway.app',
                'HTTP_X_FORWARDED_PROTO' => 'https',
                'HTTP_X_FORWARDED_PORT' => '443',
            ],
        );

        $assetUrl = null;

        app(TrustProxies::class)->handle($request, function (Request $request) use (&$assetUrl) {
            $this->assertTrue($request->isSecure());
            $this->assertSame('https://farmerproducts-production.up.railway.app', $request->getSchemeAndHttpHost());

            app('url')->setRequest($request);
            $assetUrl = asset('build/assets/app.css');

            return response('ok');
        });

        $this->assertSame(
            'https://farmerproducts-production.up.railway.app/build/assets/app.css',
            $assetUrl,
        );
    }
}
