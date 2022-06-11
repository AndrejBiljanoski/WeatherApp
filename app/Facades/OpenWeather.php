<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OpenWeather extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'OpenWeather';
    }
}
