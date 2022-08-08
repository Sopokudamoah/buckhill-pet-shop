<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\UserLoggedIn;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\V1\UserCreateRequest;
use App\Http\Requests\User\V1\UserEditRequest;
use App\Http\Requests\User\V1\UserForgotPasswordRequest;
use App\Http\Requests\User\V1\UserLoginRequest;
use App\Http\Resources\V1\AdminLoginResource;
use App\Http\Resources\V1\BaseApiResource;
use App\Models\User;
use App\Notifications\SendPasswordResetToken;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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
            $user = User::firstWhere('email', '=', $credentials['email']);

            // TODO: Change implementation from Sanctum to JWT
            $token = $user->createToken()->plainTextToken;

            UserLoggedIn::dispatch($user);

            return (new AdminLoginResource())->data(
                [
                    'token' => $token,
                    'user' => $user->only(['first_name', 'last_name', 'email', 'avatar', 'phone_number'])
                ]
            );
        }

        throw ValidationException::withMessages(['email' => 'Invalid login credentials']);
    }


    /**
     * Forgot password
     *
     * @unauthenticated
     *
     * @responseFile status=200 scenario="user provides a valid email" storage/responses/user-forgot-password-200.json
     * @responseFile status=422 scenario="user provides a invalid email" storage/responses/user-forgot-password-422.json
     */
    public function forgotPassword(UserForgotPasswordRequest $request)
    {
        $data = $request->validated();

        $trans = Password::sendResetLink($data);

        return (new BaseApiResource())->message(trans($trans));

    }

    public function resetPasswordToken()
    {
    }

    /**
     * Get user account information
     *
     * @authenticated
     *
     * @responseFile status=200 scenario="when authenticated as user" storage/responses/user-information-200.json
     */
    public function index()
    {
        $user = auth()->user();
        return (new BaseApiResource($user))->message("");
    }


    /**
     * Delete user account
     *
     * @authenticated
     *
     * @responseFile status=200 scenario="when authenticated as user" storage/responses/user-delete-200.json
     */
    public function delete()
    {
        $user = auth()->user();

        $user->delete();
        return (new BaseApiResource())->message("User deleted successfully");
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


    /**
     * Create a user account
     *
     * @unauthenticated
     *
     * @responseFile status=200 storage/responses/user-create-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/user-create-422.json
     */
    public function create(UserCreateRequest $request)
    {
        $data = $request->validated();

        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return (new BaseApiResource(
            $user->only(['first_name', 'last_name', 'email', 'avatar', 'phone_number', 'id'])
        ))->message("User created successfully");
    }


    /**
     * Edit user account
     *
     * @authenticated
     *
     *
     * @responseFile status=200 storage/responses/user-edit-200.json
     * @responseFile status=422 scenario="when attempt to update with existing email" storage/responses/user-edit-422.json
     *
     **/
    public function edit(UserEditRequest $request)
    {
        $data = $request->validated();

        $user = auth()->user();

        $user->fill($data);

        if ($user->isDirty()) {
            $user->save();
        }

        return (new BaseApiResource(
            $user->only(['first_name', 'last_name', 'email', 'avatar', 'phone_number', 'id'])
        ))->message("User updated successfully");
    }

    public function orders()
    {
    }
}
