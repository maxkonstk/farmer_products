<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateStorefrontSettingsRequest;
use App\Models\StorefrontSetting;
use App\Services\StorefrontSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StorefrontSettingsController extends Controller
{
    public function __construct(private readonly StorefrontSettingsService $settingsService)
    {
    }

    public function edit(): View
    {
        $settings = $this->settingsService->all();

        return view('admin.storefront-settings.edit', [
            'settings' => $settings,
            'brandHoursText' => implode("\n", $settings['brand']['hours'] ?? []),
            'deliveryWindowsText' => collect($settings['delivery']['windows'] ?? [])
                ->map(fn (string $label, string $key): string => "{$key} | {$label}")
                ->implode("\n"),
            'deliveryZonesText' => implode("\n", $settings['delivery']['zones'] ?? []),
            'deliveryPromisesText' => implode("\n", $settings['delivery']['promises'] ?? []),
            'storefrontPromisesText' => implode("\n", $settings['promises'] ?? []),
        ]);
    }

    public function update(UpdateStorefrontSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $record = StorefrontSetting::query()->firstOrNew();
        $record->fill([
            'brand_name' => $validated['brand_name'],
            'brand_tagline' => $validated['brand_tagline'],
            'brand_city' => $validated['brand_city'],
            'brand_address' => $validated['brand_address'],
            'brand_phone' => $validated['brand_phone'],
            'brand_email' => $validated['brand_email'],
            'hero_note' => $validated['hero_note'],
            'brand_hours' => $this->parseLines($validated['brand_hours']),
            'delivery_cutoff' => $validated['delivery_cutoff'],
            'pickup_address' => $validated['pickup_address'],
            'delivery_windows' => $this->parseMap($validated['delivery_windows']),
            'delivery_zones' => $this->parseLines($validated['delivery_zones']),
            'delivery_promises' => $this->parseLines($validated['delivery_promises']),
            'storefront_promises' => $this->parseLines($validated['storefront_promises']),
        ])->save();

        $this->settingsService->clearCache();

        return redirect()->route('admin.storefront.edit')->with('success', 'Настройки витрины обновлены.');
    }

    /**
     * @return array<int, string>
     */
    private function parseLines(string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $value) ?: [])
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return array<string, string>
     */
    private function parseMap(string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $value) ?: [])
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->mapWithKeys(function (string $line): array {
                [$key, $label] = array_pad(preg_split('/\s*\|\s*/', $line, 2) ?: [], 2, null);

                return [trim((string) $key) => trim((string) $label)];
            })
            ->filter(fn (string $label, string $key) => $key !== '' && $label !== '')
            ->all();
    }
}
