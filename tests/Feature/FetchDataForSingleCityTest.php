<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\CityWeatherData;
use Database\Factories\CityFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchDataForSingleCityTest extends TestCase
{
    /** @test */
    public function fetch_data_for_single_city_command_is_executed_successfuly_for_each_city()
    {
        $cities = City::cursor();
        foreach($cities as $city)
        {
            $this->artisan("city:get $city->id")->assertSuccessful()->assertExitCode(0);
        }
    }

    /** @test */
    public function fetch_data_for_single_city_command_returns_table_with_valid_data()
    {
        $data = CityWeatherData::factory()->count(2)->for(
            City::factory()
        )->create();
        $dataOne = $data->first();
        $dataTwo = $data->skip(1)->take(1)->first();
        $dataOneTempTrend = '';
        $dataTwoTempTrend = number_format($dataTwo->temperature - $dataOne->temperature, 2);
        $dataOneHumidTrend = '';
        $dataTwoHumidTrend = number_format($dataTwo->humidity - $dataOne->humidity, 2);
        $city = $dataOne->city;
        $this->artisan("city:get $city->id")->expectsTable([
            'Id', 'City Name', 'Latitude', 'Longitude', 'Temperature', 'Temperature Trend', 'Humidity', 'Humidity Trend', 'Measured At',
        ], [
            [$city->id, $city->name, $city->latitude, $city->longitude, 
            "$dataTwo->temperature C", 
            "$dataTwoTempTrend C", 
            "$dataTwo->humidity %", 
            "$dataTwoHumidTrend %", 
            "$dataTwo->time"],

            [$city->id, $city->name, $city->latitude, $city->longitude, 
            "$dataOne->temperature C", 
            "$dataOneTempTrend", 
            "$dataOne->humidity %", 
            "$dataOneHumidTrend", 
            "$dataOne->time"],
        ]);
    }
}
