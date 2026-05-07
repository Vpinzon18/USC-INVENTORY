<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Route; // Importante añadir esto

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
        // Forzamos que la redirección por defecto de la autenticación sea la raíz
        // Esto sobrescribe el comportamiento interno de Breeze/Fortify
        $this->app->bind('auth.home', function () {
            return '/';
        });
    \Illuminate\Pagination\Paginator::useTailwind();

    }
}