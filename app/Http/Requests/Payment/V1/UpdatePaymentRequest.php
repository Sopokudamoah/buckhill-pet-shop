<?php

namespace App\Http\Requests\Payment\V1;

use Illuminate\Foundation\Http\FormRequest;
use LVR\CreditCard\CardExpirationDate;
use LVR\CreditCard\CardNumber;

class UpdatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => 'required|in:credit_card,cash_on_delivery,bank_transfer',
            'details' => 'required|array',

            'details.holder_name' => ['required_if:type,credit_card'],
            'details.number' => ['required_if:type,credit_card', new CardNumber()],
            'details.ccv' => [
                'required_if:type,credit_card',
//                new CardCvc($this->get('details.number'))
            ],
            'details.expire_date' => ['required_if:type,credit_card', new CardExpirationDate('m/y')],

            'details.first_name' => 'required_if:type,cash_on_delivery',
            'details.last_name' => 'required_if:type,cash_on_delivery',
            'details.address' => 'required_if:type,cash_on_delivery',

            'details.swift' => 'required_if:type,bank_transfer',
            'details.iban' => 'required_if:type,bank_transfer',
            'details.name' => 'required_if:type,bank_transfer',
        ];
    }
}
