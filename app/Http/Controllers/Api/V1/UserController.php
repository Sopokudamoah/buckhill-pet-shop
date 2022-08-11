<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\UserLoggedIn;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\V1\UserCreateRequest;
use App\Http\Requests\User\V1\UserEditRequest;
use App\Http\Requests\User\V1\UserForgotPasswordRequest;
use App\Http\Requests\User\V1\UserLoginRequest;
use App\Http\Requests\User\V1\UserResetPasswordRequest;
use App\Http\Resources\Order\V1\OrderResource;
use App\Http\Resources\V1\AdminLoginResource;
use App\Http\Resources\V1\BaseApiResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\QueryBuilder;

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

        $token = Str::random(60);
        $builder = DB::table('password_resets');

        $builder->where('email', '=', $data['email'])
            ->delete();

        $builder->insert([
            'email' => $data['email'],
            'token' => $token,
            'created_at' => now()
        ]);

        $user = User::whereEmail($data['email'])->first();
        $user->sendPasswordResetNotification($token);

        return (new BaseApiResource())->message("We have emailed your password reset link!");
    }


    /**
     * Reset password with token
     *
     * @authenticated
     *
     * @responseFile status=200 scenario="when token is valid" storage/responses/user-password-reset-200.json
     * @responseFile status=403 scenario="when token is expired" storage/responses/user-password-reset-403.json
     * @responseFile status=422 scenario="when token/email doesn't exist" storage/responses/user-password-reset-422.json
     */
    public function resetPasswordToken(UserResetPasswordRequest $request)
    {
        $data = $request->validated();

        $builder = DB::table('password_resets')->where('token', '=', $data['token']);

        $password_reset = $builder->first();
        $token_request_time = Carbon::parse($password_reset->created_at);

        //Check if token is not older than an hour
        if (now()->greaterThan($token_request_time->addHour())) {
            $builder->delete();

            return (new BaseApiResource())->success(0)
                ->message("Reset token is expired")
                ->response()
                ->setStatusCode(403);
        }

        //Reset user password
        $user = User::firstWhere('email', '=', $data['email']);
        $user->password = bcrypt($data['password']);
        $user->save();

        //Delete token
        $builder->delete();

        return (new BaseApiResource())->success(1)->message("Password has been reset");
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


    /**
     * List all user's orders
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/orders-listing-200.json
     * @responseFile status=400 scenario="when filtered by disallowed field" storage/responses/orders-listing-400.json
     */
    public function orders(Request $request)
    {
        $orders = QueryBuilder::for(auth()->user()->orders())
            ->allowedFilters(['delivery_fee', 'address', 'products', 'uuid', 'payment_id', 'order_status_id'])
            ->simplePaginate($request->get('per_page', 15));

        return (new OrderResource())->resource($orders);
    }
}
