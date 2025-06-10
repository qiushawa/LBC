<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\ServerStatusService;

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
        //
    }
}
