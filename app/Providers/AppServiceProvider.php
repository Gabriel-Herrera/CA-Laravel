<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
        require_once app_path('Helpers/PriceHelper.php');
    }

    public function boot()
    {
        // Caché de categorías
        view()->composer('*', function ($view) {
            $categories = Cache::remember('categories', 60*24, function () {
                return \App\Models\Category::all();
            });
            $view->with('categories', $categories);
        });

        // Query Logger para desarrollo
        if (config('app.debug')) {
            DB::listen(function($query) {
                \Log::info(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );
            });
        }
        Paginator::useBootstrap();
    }
}

        