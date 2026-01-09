<?php

namespace App\Providers;

use App\Models\ApiToken;
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
        Auth::viaRequest('api-token', function ($request) {
            $header = $request->header('Authorization');

            if (!$header || !str_starts_with($header, 'Bearer ')) {
                return null;
            }

            $token = substr($header, 7);
            $tokenHash = hash('sha256', $token);

            $apitoken = ApiToken::query()
                ->where('token_hash', $tokenHash)
                ->whereNull('revoked_at')
                ->where('expires_at', '>', now());

            if(!$apitoken){
                return null;
            }

            // Note for last used
            $apitoken->update([
                'last_used_at'=>now(),
            ]);

            return $apitoken->user();
        });
    }
}
