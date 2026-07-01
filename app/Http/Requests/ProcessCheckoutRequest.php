<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProcessCheckoutRequest extends FormRequest
{
    private const PAYMENT_CHANNELS = [
        'qris' => [
            'qris_universal',
        ],
        'virtual_account' => [
            'va_bca',
            'va_mandiri',
            'va_bri',
            'va_bni',
        ],
        'e_wallet' => [
            'wallet_dana',
            'wallet_gopay',
            'wallet_ovo',
            'wallet_shopeepay',
        ],
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'quantity' => 'required|integer|min:1|max:5',
            'payment_method' => ['required', 'string', Rule::in(array_keys(self::PAYMENT_CHANNELS))],
            'payment_channel' => [
                'required',
                'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $method = $this->input('payment_method');
                    $allowedChannels = self::PAYMENT_CHANNELS[$method] ?? [];

                    if (! in_array($value, $allowedChannels, true)) {
                        $fail('The selected payment option is not available for this payment method.');
                    }
                },
            ],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('step', 3);
        parent::failedValidation($validator);
    }
}
