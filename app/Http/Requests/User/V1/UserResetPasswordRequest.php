<?php

namespace App\Http\Requests\User\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserResetPasswordRequest extends FormRequest
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
            'token' => ['required', Rule::exists('password_resets')->where('email', $this->request->get('email'))],
            'email' => 'required|email|exists:users',
            'password' => 'required|confirmed'
        ];
    }


    public function messages()
    {
        return [
            'token.exists' => "Invalid token"
        ];
    }
}
