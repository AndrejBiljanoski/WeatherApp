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
        $newCity = City::factory(1)->create()->first();
        $this->post('/api/city-weather', [
            'temperature' => 23.0,
            'humidity' => 90.0,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => $newCity->id
        ]);
        $this->post('/api/city-weather', [
            'temperature' => 24.0,
            'humidity' => 92.0,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 13:00:00',
            'city_id' => $newCity->id
        ]);
        $this->artisan("city:get $newCity->id")->expectsTable([
            'Id', 'City Name', 'Latitude', 'Longitude', 'Temperature', 'Temperature Trend', 'Humidity', 'Humidity Trend', 'Measured At',
        ], [
            [$newCity->id, $newCity->name, $newCity->latitude, $newCity->longitude, '24.00 C', '1.00 C', '92.00 %', '2.00 %', '2022-01-01 13:00:00'],
            [$newCity->id, $newCity->name, $newCity->latitude, $newCity->longitude, '23.00 C', '', '90.00 %', '', '2022-01-01 12:00:00'],
        ]);
    }
}
