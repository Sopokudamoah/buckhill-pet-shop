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

    /**
     * @codeCoverageIgnore
     */
    public function bodyParameters()
    {
        return [
            'title' => [
                'description' => 'Product title',
                'example' => "Dog food"
            ],
            'uuid' => [
                'description' => 'UUID of product',
                'example' => fake()->uuid()
            ],
            'category_uuid' => [
                'description' => 'UUID of category',
                'example' => fake()->uuid()
            ],
            'price' => [
                'description' => 'Price of product',
                'example' => fake()->numberBetween(0, 1000)
            ],
            'description' => [
                'description' => 'Description of product',
                'example' => fake()->sentences(2, true)
            ],
            'metadata' => [
                'description' => 'Extra information about product',
                'example' => fake()->uuid()
            ],
            'metadata[brand]' => [
                'description' => 'UUID of brand',
                'example' => fake()->uuid()
            ],
            'metadata[image]' => [
                'description' => 'UUID of file',
                'example' => fake()->uuid()
            ],
        ];
    }
}
