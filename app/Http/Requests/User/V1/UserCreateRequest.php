<?php

namespace App\Http\Requests\User\V1;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|same:password',
            'avatar' => 'required|uuid',
            'address' => 'required',
            'phone_number' => 'required',
            'is_marketing' => 'required|bool',
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function bodyParameters()
    {
        $password = fake()->password();
        return [
            'first_name' => [
                'description' => 'User\'s first name',
                'example' => fake()->firstName()
            ],
            'last_name' => [
                'description' => 'User\'s last name',
                'example' => fake()->lastName()
            ],
            'email' => [
                'description' => 'User\'s email',
                'example' => fake()->safeEmail()
            ],
            'password' => [
                'description' => 'A strong password',
                'example' => $password
            ],
            'password_confirmation' => [
                'description' => 'Confirm password',
                'example' => $password
            ],
            'avatar' => [
                'description' => 'Avatar image UUID',
                'example' => fake()->uuid()
            ],
            'address' => [
                'description' => 'User\'s address',
                'example' => fake()->address()
            ],
            'phone_number' => [
                'description' => 'User\'s phone number',
                'example' => fake()->e164PhoneNumber()
            ],
            'is_marketing' => [
                'description' => 'Is marketing',
                'example' => fake()->randomElement(['0', '1'])
            ],
        ];
    }
}
