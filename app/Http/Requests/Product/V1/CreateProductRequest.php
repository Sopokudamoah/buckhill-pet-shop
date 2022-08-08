<?php

namespace App\Http\Requests\Product\V1;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'title' => 'required|string',
            'uuid' => 'sometimes|nullable|uuid|unique:products',
            'category_uuid' => 'required|uuid|exists:categories,uuid',
            'price' => 'required|numeric|min:0',
            'description' => 'required',
            'metadata' => 'sometimes|nullable|array',
            'metadata.brand' => 'sometimes|nullable|uuid|exists:brands,uuid',
//            'metadata.file' => 'sometimes|nullable|uuid|exists:files,uuid',
        ];
    }
}
