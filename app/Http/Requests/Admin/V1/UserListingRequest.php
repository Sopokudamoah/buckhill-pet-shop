<?php

namespace App\Http\Requests\Admin\V1;

use Illuminate\Foundation\Http\FormRequest;

class UserListingRequest extends FormRequest
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
            'page' => 'sometimes|int',
            'limit' => 'sometimes|int',
            'filter.first_name' => 'string',
            'filter.last_name' => 'string',
            'filter.email' => 'string',
            'filter.phone_number' => 'string',
            'sorting' => 'sometimes|string',
        ];
    }


    /**
     * @codeCoverageIgnore
     */
    public function queryParameters()
    {
        return [
            'page' => [
                'description' => 'The page number',
                'example' => 1
            ],
            'limit' => [
                'description' => 'Number of resources per page',
                'example' => 15
            ],
            'filter.first_name' => [
                'description' => 'Filter by the first_name field',
                'example' => fake()->firstName()
            ],
            'filter.last_name' => [
                'description' => 'Filter by the last_name field',
                'example' => fake()->lastName()
            ],
            'filter.email' => [
                'description' => 'Filter by the email field',
                'example' => fake()->safeEmail()
            ],
            'filter.phone_number' => [
                'description' => 'Filter by the phone_number field',
                'example' => fake()->e164PhoneNumber()
            ],
            'sorting' => [
                'description' => 'Sort by field. You can sort by multiple fields by comma-separating them. A negative(-) prefix denotes a descending sort. ',
                'example' => 'name,email'
            ]
        ];
    }
}
