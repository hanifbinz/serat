<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        // Membuat pintu gerbang khusus 'administrator'
        Gate::define('is-administrator', function ($user) {
            return $user->role === 'administrator';
        });
    }
}