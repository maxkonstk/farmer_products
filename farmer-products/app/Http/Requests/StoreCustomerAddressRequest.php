<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:60'],
            'recipient_name' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'min:10', 'max:30'],
            'city' => ['required', 'string', 'max:80'],
            'address_line' => ['required', 'string', 'min:10', 'max:180'],
            'comment' => ['nullable', 'string', 'max:180'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }
}
