<?php

namespace App\Http\Controllers;

use App\Builders\OpenWeatherBuilder;
use App\Facades\OpenWeather;
use App\Http\Traits\ApiResponseTrait;
use App\Models\City;

class CityWeatherController extends Controller
{
    use ApiResponseTrait;
    
    public function show($id)
    {
        $city = City::find($id); 
        $openWeather = new OpenWeatherBuilder($city->latitude, $city->longitude); 
        $response = OpenWeather::get($openWeather);
        return $this->successResponse($response);
    }
}
