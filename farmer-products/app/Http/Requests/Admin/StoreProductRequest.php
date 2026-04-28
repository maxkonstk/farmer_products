<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150', Rule::unique('products', 'name')],
            'description' => ['required', 'string', 'min:20'],
            'producer_name' => ['nullable', 'string', 'max:120'],
            'origin_location' => ['nullable', 'string', 'max:120'],
            'seasonality' => ['nullable', 'string', 'max:80'],
            'taste_notes' => ['nullable', 'string', 'max:255'],
            'storage_info' => ['nullable', 'string', 'max:255'],
            'shelf_life' => ['nullable', 'string', 'max:120'],
            'delivery_note' => ['nullable', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:80'],
            'ingredients' => ['nullable', 'string', 'max:2000'],
            'highlights' => ['nullable', 'string', 'max:2000'],
            'gallery' => ['nullable', 'string', 'max:3000'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'weight' => ['nullable', 'string', 'max:50'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }
}
