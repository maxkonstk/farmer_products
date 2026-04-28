<?php

namespace App\Http\Requests\Admin;

use App\Models\PromoBlock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePromoBlockRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:150', Rule::unique('promo_blocks', 'name')],
            'eyebrow' => ['nullable', 'string', 'max:80'],
            'badge' => ['nullable', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:170'],
            'body' => ['required', 'string', 'min:20'],
            'cta_label' => ['nullable', 'string', 'max:80'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'theme' => ['required', Rule::in(array_keys(PromoBlock::themes()))],
            'placement' => ['required', Rule::in(array_keys(PromoBlock::placements()))],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_published' => ['nullable', 'boolean'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'collection_id' => ['nullable', 'integer', 'exists:collections,id'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $targetCount = collect([
                $this->input('category_id'),
                $this->input('collection_id'),
                $this->input('product_id'),
            ])->filter(fn ($value) => filled($value))->count();

            if ($targetCount > 1) {
                $validator->errors()->add('target', 'Для промо укажите только одну привязку: категорию, подборку или товар.');
            }
        });
    }
}
