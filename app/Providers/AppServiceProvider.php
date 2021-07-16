<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Observers\PermissionModelObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Permission::observe(PermissionModelObserver::class);
    }
}
