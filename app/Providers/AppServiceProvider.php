<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Route; // Importante añadir esto

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->app->bind('auth.home', function () {
            return '/';
        });
        \Illuminate\Pagination\Paginator::useTailwind();
    }
}
