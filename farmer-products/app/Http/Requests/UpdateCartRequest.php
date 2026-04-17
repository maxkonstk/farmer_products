<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
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
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'quantity.required' => 'Укажите количество товара.',
            'quantity.integer' => 'Количество должно быть целым числом.',
            'quantity.min' => 'Количество не может быть меньше 1.',
            'quantity.max' => 'Слишком большое количество товара.',
        ];
    }
}
