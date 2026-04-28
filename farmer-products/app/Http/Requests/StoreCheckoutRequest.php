<?php

namespace App\Http\Requests;

use App\Services\StorefrontSettingsService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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
        $deliveryWindows = app(StorefrontSettingsService::class)->delivery()['windows'] ?? [];

        return [
            'customer_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'min:10', 'max:30'],
            'email' => ['required', 'email', 'max:120'],
            'fulfillment_method' => ['required', Rule::in(['delivery', 'pickup'])],
            'delivery_window' => ['nullable', Rule::in(array_keys($deliveryWindows))],
            'substitution_preference' => ['nullable', Rule::in(['call', 'best-match', 'remove'])],
            'saved_address_id' => [
                'nullable',
                'integer',
                Rule::exists('customer_addresses', 'id')->where(
                    fn ($query) => $query->where('user_id', $this->user()?->id ?? 0)
                ),
            ],
            'address' => ['nullable', 'string', 'min:10', 'max:255'],
            'comment' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (
                $this->input('fulfillment_method') === 'delivery'
                && blank($this->input('address'))
                && blank($this->input('saved_address_id'))
            ) {
                $validator->errors()->add('address', 'Укажите адрес доставки или выберите сохраненный адрес.');
            }
        });
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
            'fulfillment_method.required' => 'Выберите способ получения заказа.',
            'address.min' => 'Адрес должен быть более подробным.',
            'saved_address_id.exists' => 'Выберите адрес из своего списка.',
            'comment.max' => 'Комментарий не должен превышать 500 символов.',
        ];
    }
}
