<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\UserLoggedIn;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AdminLoginRequest;
use App\Http\Resources\V1\AdminLoginResource;
use App\Http\Resources\V1\BaseApiResource;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * @group Admin
 *
 *
 * This API endpoint will allow to create a new admin account, login and logout an admin account, as well it will enable the following features:
 * - All users listing (non-admins)
 * - Edit and Delete user’s accounts
 * - Admins accounts cannot be deleted or edited
 */
class AdminController extends Controller
{
    /**
     * Login an admin account
     *
     * @unauthenticated
     *
     * @responseFile status=200 scenario="when authenticated as admin" storage/responses/admin-login-200.json
     * @responseFile status=422 scenario="when credentials are invalid as admin" storage/responses/admin-login-422.json
     * @responseFile status=422 scenario="when not an admin" storage/responses/user-admin-login-422.json
     */
    public function login(AdminLoginRequest $request)
    {
        // Get validated data from request
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $admin = Admin::firstWhere('email', '=', $credentials['email']);

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

    /**
     * Logout an admin account
     *
     * @authenticated
     *
     * @responseFile status=200 scenario="when authenticated as admin" storage/responses/admin-logout-200.json
     * @responseFile status=401 scenario="when unauthenticated as admin" storage/responses/admin-logout-401.json
     */
    public function logout(Request $request)
    {
        //TODO: Re-write for JWT implementation
        $token = $request->user()->currentAccessToken();
        $token->delete();

        return (new BaseApiResource())->message("Admin logged out");
    }

    public function create(Request $request)
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
