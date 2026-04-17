<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
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
            'customer_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'min:10', 'max:30'],
            'email' => ['required', 'email', 'max:120'],
            'address' => ['required', 'string', 'min:10', 'max:255'],
            'comment' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Укажите имя получателя.',
            'phone.required' => 'Укажите номер телефона.',
            'phone.min' => 'Телефон должен содержать не менее 10 символов.',
            'email.required' => 'Укажите адрес электронной почты.',
            'email.email' => 'Введите корректный адрес электронной почты.',
            'address.required' => 'Укажите адрес доставки.',
            'address.min' => 'Адрес должен быть более подробным.',
            'comment.max' => 'Комментарий не должен превышать 500 символов.',
        ];
    }
}
