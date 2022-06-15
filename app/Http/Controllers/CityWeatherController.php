<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityWeatherManageRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Models\CityWeatherData;

class CityWeatherController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cityWeather = CityWeatherData::with('city')->cursor();
        return $this->successResponse($cityWeather);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityWeatherManageRequest $request)
    {
        $cityWeather = CityWeatherData::create($request->all());
        return redirect()->route('city-weather.show', $cityWeather->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cityWeather = CityWeatherData::with('city')->findOrFail($id);
        return $this->successResponse($cityWeather);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CityWeatherManageRequest $request, $id)
    {
        $cityWeather = CityWeatherData::findOrFail($id);
        $cityWeather->update($request->except('id'));
        return redirect()->route('city-weather.show', $cityWeather->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $cityWeather = CityWeatherData::findOrFail($id);
            $cityWeather->delete();
            return $this->successResponse([]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 204);
        }
    }
}
