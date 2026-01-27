<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
use App\Extensions\SanctumBearerGuard;


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

        ResetPassword::createUrlUsing(function ($user, string $token) {
            // return 'https://example.com/reset-password?token=' . $token;
            return env('APP_FE') . '/auth/new-password/' . $token . '?email=' . urlencode($user->email);
        });
        //
        Auth::extend('sanctum-bearer', function ($app, $name, array $config) {
            return new SanctumBearerGuard(
                Auth::createUserProvider($config['provider']),
                $app['request']
            );
        });
    }
}
