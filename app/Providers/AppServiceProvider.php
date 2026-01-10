<?php

namespace App\Providers;

use App\Models\ApiToken;
use App\Providers\Auth\TokenHandler;
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
        Auth::viaRequest('api-token', function (Request $request) {
            return (new TokenHandler())->authenticate($request);
        });
    }
}
