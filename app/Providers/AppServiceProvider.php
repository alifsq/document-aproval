<?php

namespace App\Providers;

use App\Models\ApiToken;
use App\Providers\Auth\TokenHandler;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::extend('api_token', function ($app, $name, array $config) {
            // Make sure to use the correct namespace for ApiTokenGuard
            return new \App\Guards\ApiTokenGuard(
                Auth::createUserProvider($config['provider']),
                $app->make('request')
            );
        });
    }
}
