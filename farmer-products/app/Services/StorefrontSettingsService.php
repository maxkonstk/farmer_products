<?php

namespace App\Services;

use App\Models\StorefrontSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class StorefrontSettingsService
{
    public function all(): array
    {
        if (! Schema::hasTable('storefront_settings')) {
            return $this->fallback();
        }

        return Cache::remember('storefront.settings.snapshot', now()->addMinutes(30), function (): array {
            $fallback = $this->fallback();
            $record = StorefrontSetting::query()->first();

            if (! $record) {
                return $fallback;
            }

            return [
                'brand' => [
                    'name' => $record->brand_name ?: $fallback['brand']['name'],
                    'tagline' => $record->brand_tagline ?: $fallback['brand']['tagline'],
                    'city' => $record->brand_city ?: $fallback['brand']['city'],
                    'address' => $record->brand_address ?: $fallback['brand']['address'],
                    'phone' => $record->brand_phone ?: $fallback['brand']['phone'],
                    'email' => $record->brand_email ?: $fallback['brand']['email'],
                    'hours' => $record->brand_hours ?: $fallback['brand']['hours'],
                    'hero_note' => $record->hero_note ?: $fallback['brand']['hero_note'],
                ],
                'delivery' => [
                    'cutoff' => $record->delivery_cutoff ?: $fallback['delivery']['cutoff'],
                    'windows' => $record->delivery_windows ?: $fallback['delivery']['windows'],
                    'pickup_address' => $record->pickup_address ?: $fallback['delivery']['pickup_address'],
                    'zones' => $record->delivery_zones ?: $fallback['delivery']['zones'],
                    'promises' => $record->delivery_promises ?: $fallback['delivery']['promises'],
                ],
                'promises' => $record->storefront_promises ?: $fallback['promises'],
                'analytics' => [
                    'provider' => $this->resolveAnalyticsProvider($record->analytics_provider, $fallback['analytics']['provider'] ?? 'none'),
                    'ga_measurement_id' => $record->ga_measurement_id ?: ($fallback['analytics']['ga_measurement_id'] ?? null),
                    'gtm_container_id' => $record->gtm_container_id ?: ($fallback['analytics']['gtm_container_id'] ?? null),
                    'track_web_vitals' => $record->track_web_vitals ?? ($fallback['analytics']['track_web_vitals'] ?? true),
                    'debug_mode' => $record->analytics_debug ?? ($fallback['analytics']['debug_mode'] ?? false),
                    'requires_consent' => $fallback['analytics']['requires_consent'] ?? true,
                    'consent_version' => $fallback['analytics']['consent_version'] ?? '2026-04',
                ],
            ];
        });
    }

    public function brand(): array
    {
        return $this->all()['brand'];
    }

    public function delivery(): array
    {
        return $this->all()['delivery'];
    }

    public function promises(): array
    {
        return $this->all()['promises'];
    }

    public function analytics(): array
    {
        return $this->all()['analytics'];
    }

    public function clearCache(): void
    {
        Cache::forget('storefront.settings.snapshot');
    }

    private function fallback(): array
    {
        return [
            'brand' => config('shop.brand', []),
            'delivery' => config('shop.delivery', []),
            'promises' => config('shop.promises', []),
            'analytics' => config('shop.analytics', []),
        ];
    }

    private function resolveAnalyticsProvider(?string $value, string $fallback): string
    {
        $provider = strtolower(trim((string) $value));

        return in_array($provider, ['none', 'ga4', 'gtm'], true) ? $provider : $fallback;
    }
}
