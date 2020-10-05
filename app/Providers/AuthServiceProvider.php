<?php

namespace App\Providers;

use App\Auth\AuthUserProvider;
use App\Auth\JwtGuard;
use App\Models\Address;
use App\Policies\AddressPolicy;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Address::class => AddressPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerProviders();
        $this->registerGuards();
    }

    protected function registerProviders(): void
    {
        Auth::provider('auth.user.provider', fn (Application $app, array $config): UserProvider =>
            new AuthUserProvider()
        );
    }

    protected function registerGuards(): void
    {
        Auth::extend('jwt', function (Application $app, $name, array $config): Guard {
            /** @var Request $request */
            $request = $app['request'];

            return new JwtGuard ($request->bearerToken(), Auth::createUserProvider($config['provider']));
        });
    }
}
