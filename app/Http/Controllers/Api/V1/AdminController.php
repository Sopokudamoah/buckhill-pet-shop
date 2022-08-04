<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AdminLoginRequest;
use App\Http\Resources\V1\AdminLoginResource;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function create(Request $request)
    {
    }

    /**
     * @param AdminLoginRequest $request
     * @return AdminLoginResource
     */
    public function login(AdminLoginRequest $request)
    {
        // Get validated data from request
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $admin = Admin::firstWhere('email', '=', $credentials['email']);

            // TODO: Change implementation from Sanctum to JWT
            $token = $admin->createToken($admin->full_name)->plainTextToken;

            return (new AdminLoginResource())->data(
                [
                    'token' => $token,
                    'user' => $admin->only(['first_name', 'last_name', 'email', 'avatar', 'phone_number'])
                ]
            );
        }

        throw ValidationException::withMessages(['email' => 'Invalid login credentials']);
    }

    public function logout(Request $request)
    {
    }

    public function userListing(Request $request)
    {
    }

    public function userEdit(Request $request, User $user)
    {
    }

    public function userDelete(Request $request, User $user)
    {
    }
}
