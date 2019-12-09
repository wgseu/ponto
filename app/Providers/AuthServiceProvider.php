<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Interfaces\AuthorizableInterface;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Tymon\JWTAuth\Http\Parser\AuthHeaders;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $headerParser = new AuthHeaders();
        auth()->parser()->setChain([$headerParser]);
        $this->app->forgetInstance('tymon.jwt.parser');
        $this->app->forgetInstance('tymon.jwt');
        $headerParser = new AuthHeaders();
        $headerParser->setHeaderPrefix('Device');
        auth('device')->parser()->setChain([$headerParser]);
        $this->app->forgetInstance('tymon.jwt.parser');
        $this->app->forgetInstance('tymon.jwt');
        $headerParser = new AuthHeaders();
        $headerParser->setHeaderPrefix('Authorizer');
        auth('authorizer')->parser()->setChain([$headerParser]);

        Gate::before(function (Authorizable $user, string $ability) {
            if ($user instanceof AuthorizableInterface) {
                return $user->hasPermissionTo($ability) ?: null;
            }
        });
    }
}
