<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);
        // use this when you dont want the return values as a data object
        // Resource::withoutWrapping();
    }
}
