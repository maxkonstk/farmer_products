<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStorefrontSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'brand_name' => ['required', 'string', 'max:120'],
            'brand_tagline' => ['required', 'string', 'max:255'],
            'brand_city' => ['required', 'string', 'max:80'],
            'brand_address' => ['required', 'string', 'max:180'],
            'brand_phone' => ['required', 'string', 'max:30'],
            'brand_email' => ['required', 'email', 'max:120'],
            'hero_note' => ['required', 'string', 'max:1000'],
            'brand_hours' => ['required', 'string', 'max:2000'],
            'delivery_cutoff' => ['required', 'string', 'max:255'],
            'pickup_address' => ['required', 'string', 'max:180'],
            'delivery_windows' => ['required', 'string', 'max:4000'],
            'delivery_zones' => ['required', 'string', 'max:4000'],
            'delivery_promises' => ['required', 'string', 'max:4000'],
            'storefront_promises' => ['required', 'string', 'max:4000'],
            'analytics_provider' => ['required', 'in:none,ga4,gtm'],
            'ga_measurement_id' => ['nullable', 'required_if:analytics_provider,ga4', 'string', 'max:40'],
            'gtm_container_id' => ['nullable', 'required_if:analytics_provider,gtm', 'string', 'max:40'],
            'track_web_vitals' => ['nullable', 'boolean'],
            'analytics_debug' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'track_web_vitals' => $this->boolean('track_web_vitals'),
            'analytics_debug' => $this->boolean('analytics_debug'),
        ]);
    }
}
