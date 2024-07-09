<?php

namespace App\Providers;

use App\Guards\JwtGuard;
use App\Services\JwtService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        Auth::extend('jwt', function ($app, $name, array $config) {
            $provider = Auth::createUserProvider($config['provider']);
            $jwtService = $app->make(JwtService::class);
            if ($provider) {
                return new JwtGuard($provider, $app['request'], $jwtService);
            }
        });
    }
}
