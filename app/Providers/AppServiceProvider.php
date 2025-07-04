<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\ServerStatusService;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ServerStatusService::class, function () {
            return new ServerStatusService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
