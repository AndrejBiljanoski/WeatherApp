<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityWeatherManageRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Models\CityWeatherData;

class CityWeatherController extends Controller
{
    use ApiResponseTrait;

    public function store(CityWeatherManageRequest $request)
    {
        $cityWeather = CityWeatherData::create($request->all());
        return redirect()->route('city-weather.get', $cityWeather->id);
    }

    public function update(CityWeatherManageRequest $request)
    {
        $request->validate([
            'id' => 'required|exists:city_weather_data,id'
        ]);
        $cityWeather = CityWeatherData::findOrFail($request->id);
        $cityWeather->update($request->except('id'));
        return redirect()->route('city-weather.get', $cityWeather->id);
    }
    
    public function show($id)
    {
        $cityWeather = CityWeatherData::findOrFail($id);
        return $this->successResponse($cityWeather);
    }
}
