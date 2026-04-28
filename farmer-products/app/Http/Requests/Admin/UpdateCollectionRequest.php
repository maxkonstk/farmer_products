<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class UpdateCollectionRequest extends StoreCollectionRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $collectionId = $this->route('collection')?->id;

        $rules['name'] = ['required', 'string', 'max:150', Rule::unique('collections', 'name')->ignore($collectionId)];

        return $rules;
    }
}
