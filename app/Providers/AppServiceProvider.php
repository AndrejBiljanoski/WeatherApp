<?php

namespace App\Providers;

use App\Http\Services\OpenWeatherApiService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->singleton('OpenWeather', function ($app) {
            return new OpenWeatherApiService();
        });
    }
}
