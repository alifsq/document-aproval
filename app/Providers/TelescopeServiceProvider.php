<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\Telescope;

class TelescopeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
{
    if (! app()->isLocal()) {
        Telescope::stopRecording();
    }
}

public function boot(): void
{
    Telescope::filter(function () {
        return true; // record everything (local only)
    });
}
    public function gate()
    {
        return app()->isLocal();
    }
}
