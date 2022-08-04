<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AdminLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function create(Request $request)
    {
    }

    public function login(AdminLoginRequest $request)
    {
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
