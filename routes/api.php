<?php

use App\Http\Controllers\CityWeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::controller(CityWeatherController::class)->prefix('weather/city')->group(function () {
    Route::get('/{id}', 'show')->name('city-weather.get');
    Route::post('','store')->name('city-weather.add');
    Route::patch('','update')->name('city-weather.update');
});
