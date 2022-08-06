<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\UserLoggedIn;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\AdminCreateRequest;
use App\Http\Requests\Admin\V1\AdminLoginRequest;
use App\Http\Requests\Admin\V1\AdminUserDeleteRequest;
use App\Http\Requests\Admin\V1\AdminUserEditRequest;
use App\Http\Requests\Admin\V1\UserListingRequest;
use App\Http\Resources\V1\AdminLoginResource;
use App\Http\Resources\V1\BaseApiResource;
use App\Http\Resources\V1\UserResource;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Admin endpoint
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

    /**
     * Create an admin account
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/admin-user-create-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/admin-user-create-422.json
     */
    public function create(AdminCreateRequest $request)
    {
        $data = $request->validated();

        $data['password'] = bcrypt($data['password']);
        $user = Admin::create($data);
        return (new BaseApiResource(
            $user->only(['first_name', 'last_name', 'email', 'avatar', 'phone_number', 'id'])
        ))->message("User created successfully");
    }


    /**
     * List all users
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/admin-user-listing-200.json
     * @responseFile status=400 scenario="when filtered by disallowed field" storage/responses/admin-user-listing-400.json
     */
    public function userListing(UserListingRequest $request)
    {
        $users = QueryBuilder::for(User::query())
            ->select(['first_name', 'last_name', 'email', 'avatar', 'phone_number', 'id'])
            ->allowedFilters(['first_name', 'last_name','email', 'phone_number'])
            ->simplePaginate($request->get('per_page', 15));

        return (new UserResource())->resource($users);
    }

    /**
     * Edit user account
     *
     * @authenticated
     *
     * @urlParam uuid string required The UUID of the user.
     *
     * @responseFile status=200 storage/responses/admin-user-edit-200.json
     * @responseFile status=403 scenario="when attempt admin account edit" storage/responses/admin-user-edit-403.json
     * @responseFile status=422 scenario="when attempt to update with existing email" storage/responses/admin-user-edit-422.json
     */
    public function userEdit(AdminUserEditRequest $request, User $user)
    {
        $data = $request->validated();

        $user->fill($data);

        if ($user->isDirty()) {
            $user->save();
        }

        return (new BaseApiResource(
            $user->only(['first_name', 'last_name', 'email', 'avatar', 'phone_number', 'id'])
        ))->message("User created successfully");
    }


    /**
     * Delete user account
     *
     * @authenticated
     *
     * @urlParam uuid string required The UUID of the user.
     *
     * @responseFile status=200 storage/responses/admin-user-delete-200.json
     * @responseFile status=403 scenario="when attempt to delete an admin account edit" storage/responses/admin-user-delete-403.json
     * @responseFile status=404 scenario="when attempt to delete a non-existing user" storage/responses/admin-user-delete-404.json
     */
    public function userDelete(AdminUserDeleteRequest $request, User $user)
    {
        $user->delete();
        return (new BaseApiResource())->message("User deleted successfully");
    }
}
