<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\UserLoggedIn;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\V1\UserLoginRequest;
use App\Http\Resources\V1\AdminLoginResource;
use App\Http\Resources\V1\BaseApiResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


/**
 * @group User endpoint
 *
 *
 * This endpoint will handle the CRUD methods for the user, as well it will enable the following features:
 * - Login/logout
 * - forgot/reset password
 * - listing all user orders
 */
class UserController extends Controller
{
    /**
     * Login a user account
     *
     * @unauthenticated
     *
     * @responseFile status=200 scenario="when authenticated as user" storage/responses/user-login-200.json
     * @responseFile status=422 scenario="when credentials are invalid as user" storage/responses/user-login-422.json
     * @responseFile status=422 scenario="when not a user" storage/responses/admin-user-login-422.json
     */
    public function login(UserLoginRequest $request)
    {
        // Get validated data from request
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $admin = User::firstWhere('email', '=', $credentials['email']);

            // TODO: Change implementation from Sanctum to JWT
            $token = $admin->createToken($admin->full_name)->plainTextToken;

            UserLoggedIn::dispatch($admin);

            return (new AdminLoginResource())->data(
                [
                    'token' => $token,
                    'user' => $admin->only(['first_name', 'last_name', 'email', 'avatar', 'phone_number'])
                ]
            );
        }

        throw ValidationException::withMessages(['email' => 'Invalid login credentials']);
    }

    public function forgotPassword()
    {
    }

    public function resetPasswordToken()
    {
    }

    public function index()
    {
    }

    public function delete()
    {
    }

    /**
     * Logout a user account
     *
     * @authenticated
     *
     * @responseFile status=200 scenario="when authenticated as user" storage/responses/user-logout-200.json
     * @responseFile status=401 scenario="when unauthenticated as user" storage/responses/user-logout-401.json
     */
    public function logout(Request $request)
    {
        //TODO: Re-write for JWT implementation
        $token = $request->user()->currentAccessToken();
        $token->delete();

        return (new BaseApiResource())->message("User logged out");
    }

    public function create()
    {
    }

    public function edit()
    {
    }

    public function orders()
    {
    }
}
