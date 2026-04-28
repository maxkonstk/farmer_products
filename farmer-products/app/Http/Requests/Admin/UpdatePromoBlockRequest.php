<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class UpdatePromoBlockRequest extends StorePromoBlockRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $promoBlockId = $this->route('promo')?->id;

        $rules['name'] = ['required', 'string', 'max:150', Rule::unique('promo_blocks', 'name')->ignore($promoBlockId)];

        return $rules;
    }
}
