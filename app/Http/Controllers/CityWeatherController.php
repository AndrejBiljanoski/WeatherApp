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
        return redirect()->route('city-weather.show', $cityWeather->id);
    }

    public function update(CityWeatherManageRequest $request, $id)
    {
        $cityWeather = CityWeatherData::findOrFail($id);
        $cityWeather->update($request->except('id'));
        return redirect()->route('city-weather.show', $cityWeather->id);
    }

    public function show($id)
    {
        $cityWeather = CityWeatherData::with('city')->findOrFail($id);
        return $this->successResponse($cityWeather);
    }

    public function destroy($id)
    {
        try{
            $cityWeather = CityWeatherData::findOrFail($id);
            $cityWeather->delete();
            return $this->successResponse([]);
        }
        catch(\Exception $e)
        {
            return $this->errorResponse($e->getMessage(), 204);
        }
    }
}
