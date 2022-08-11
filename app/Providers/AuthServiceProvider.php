<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Guards\JwtAuthGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('jwt', function (Request $request) {
            if ($request->bearerToken()) {
                $jwt_guard = new JwtAuthGuard($request->bearerToken());
                return $jwt_guard->getUserFromToken();
            }

            return null;
        });
    }
}
