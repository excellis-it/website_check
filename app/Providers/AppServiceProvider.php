<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use App\Models\UrlManagement;
use App\Policies\UrlManagementPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::$keyPath = storage_path(); // Fix for uninitialized typed static property
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::ignoreRoutes();

        // Register policies
        Gate::policy(UrlManagement::class, UrlManagementPolicy::class);
    }
}
