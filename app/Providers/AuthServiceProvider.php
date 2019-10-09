<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Interfaces\AuthorizableInterface;
use App\Exceptions\PermissionDoesNotExist;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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

        Gate::before(function (Authorizable $user, string $ability) {
            try {
                if ($user instanceof AuthorizableInterface) {
                    return $user->hasPermissionTo($ability) ?: null;
                }
            } catch (PermissionDoesNotExist $e) {
            }
        });
    }
}
