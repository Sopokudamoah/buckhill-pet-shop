<?php

namespace App\Http\Requests\V1;

use Faker\Factory;
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

    public function bodyParameters()
    {
        $faker = Factory::create();

        return [
            'email' => [
                'description' => 'A valid email of admin user',
                'example' => $faker->safeEmail()
            ],
            'password' => [
                'description' => 'Password of admin user',
                'example' => $faker->password(8, 10),
            ]
        ];
    }
}
