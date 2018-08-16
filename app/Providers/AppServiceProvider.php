<?php

namespace App\Providers;

use App\Contracts\WeatherServiceContract;
use App\Services\Weather\OpenWeatherCacheService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(WeatherServiceContract::class, OpenWeatherCacheService::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
