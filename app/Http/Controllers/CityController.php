<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityManageRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::cursor();
        return $this->successResponse($cities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityManageRequest $request)
    {
        try {
            $newCity = City::create($request->all());
            return redirect()->route('city.show', $newCity->id);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 204);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $city = City::findOrFail($id);
        return $this->successResponse($city);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CityManageRequest $request, $id)
    {
        try {
            $city = City::findOrFail($id);
            $city->update($request->except('id'));
            return redirect()->route('city.show', $city->id);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 204);
        }
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
            $cityWeather = City::findOrFail($id);
            $cityWeather->delete();
            return $this->successResponse([]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 204);
        }
    }
}
