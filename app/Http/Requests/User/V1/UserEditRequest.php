<?php

namespace App\Http\Requests\User\V1;

use App\Http\Requests\Admin\V1\AdminUserEditRequest;
use Illuminate\Validation\Rule;

class UserEditRequest extends AdminUserEditRequest
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
        $user = auth()->user();

        return [
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'email' => [
                'sometimes',
                'required',
                Rule::unique('users')
                    ->when(!empty($user), function ($q) use ($user) {
                        $q->ignoreModel($user);
                    })
            ],
            'password' => 'sometimes|nullable|confirmed',
            'password_confirmation' => 'sometimes|nullable|required_with:password|same:password',
            'avatar' => 'sometimes|required|uuid',
            'address' => 'sometimes|required',
            'phone_number' => 'sometimes|required',
            'is_marketing' => 'sometimes|required|bool',

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
