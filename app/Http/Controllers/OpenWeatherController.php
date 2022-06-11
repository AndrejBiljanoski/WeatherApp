<?php

namespace App\Http\Controllers;

use App\Facades\OpenWeather;
use App\Http\Traits\ApiResponseTrait;

class OpenWeatherController extends Controller
{
    use ApiResponseTrait;

    public function getData($lat, $lon)
    {
        $response = OpenWeather::getByLatLon([
            'lat' => $lat,
            'lon' => $lon
        ]);
        return $this->successResponse($response);
    }
}
