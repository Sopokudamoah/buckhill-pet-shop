<?php

namespace App\Http\Requests\Order\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'address' => 'sometimes|required|array',
            'products' => 'sometimes|required|array',
            'payment_id' => 'sometimes|required|exists:payments,id',

            'address.billing' => 'sometimes|required|string',
            'address.shipping' => 'sometimes|required|string',

            'products.*.product' => 'sometimes|required|uuid|exists:products,uuid',
            'products.*.quantity' => 'sometimes|required|numeric|min:0',
        ];
    }
}
