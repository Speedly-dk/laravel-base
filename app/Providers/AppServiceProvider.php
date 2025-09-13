<?php

namespace App\Providers;

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
        // Disable mass assignment protection globally for all models
        // This removes the need for $fillable or $guarded properties
        // IMPORTANT: Ensure proper validation is always in place
        Model::unguard();
    }
}
