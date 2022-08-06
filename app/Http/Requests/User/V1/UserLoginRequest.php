<?php

namespace App\Http\Requests\User\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', Rule::exists('users', 'email')->where('is_admin', 0)],
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
                'description' => 'A valid email of user',
                'example' => fake()->safeEmail()
            ],
            'password' => [
                'description' => 'Password of user',
                'example' => fake()->password(8, 10),
            ]
        ];
    }
}
