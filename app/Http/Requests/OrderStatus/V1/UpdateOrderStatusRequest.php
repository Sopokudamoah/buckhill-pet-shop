<?php

namespace App\Http\Requests\OrderStatus\V1;

use App\Models\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderStatusRequest extends FormRequest
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
        /** @var OrderStatus $order_status */
        $order_status = $this->route('order_status');
        return [
            'title' => [
                'required',
                'string',
                Rule::unique('order_statuses', 'title')
                    ->when(!empty($order_status), function ($q) use ($order_status) {
                        $q->ignoreModel($order_status);
                    })
            ]
        ];
    }
}
