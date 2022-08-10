<?php

namespace App\Http\Requests\Order\V1;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'address' => 'required|array',
            'products' => 'required|array',
            'payment_id' => 'required|exists:payments,id',
            'order_status_id' => 'required|exists:order_statuses,id',
            'user_id' => 'required|exists:users,id',

            'address.billing' => 'required|string',
            'address.shipping' => 'required|string',

            'products.*.product' => 'required|uuid|exists:products,uuid',
            'products.*.quantity' => 'required|numeric|min:0',
        ];
    }
}
