<?php

namespace App\Http\Requests\Admin\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminLoginRequest extends FormRequest
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
            'email' => ['required', 'email', Rule::exists('users', 'email')->where('is_admin', 1)],
            'password' => 'required'
        ];
    }


    /**
     * @codeCoverageIgnore
     */
    public function bodyParameters()
    {
        return [
            'email' => [
                'description' => 'A valid email of admin user',
                'example' => fake()->safeEmail()
            ],
            'password' => [
                'description' => 'Password of admin user',
                'example' => fake()->password(8, 10),
            ]
        ];
    }
}
