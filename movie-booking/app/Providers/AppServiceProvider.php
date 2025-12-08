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
        // Share cinemas with header layout
        view()->composer('layouts.header', function ($view) {
            $cinemas = \App\Models\Cinema::orderBy('name')->get();
            $view->with('cinemas', $cinemas);
        });
    }
}
