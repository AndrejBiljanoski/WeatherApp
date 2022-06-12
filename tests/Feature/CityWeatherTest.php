<?php

namespace Tests\Feature;

use App\Models\CityWeatherData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CityWeatherTest extends TestCase
{
    /** @test */
    public function temperature_is_required()
    {
        $response = $this->post('/api/weather/city', [
            'temperature' => '',
            'humidity' => 99,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => 1
        ]);

        $response->assertSessionHasErrors('temperature');
    }

    /** @test */
    public function humidity_is_required()
    {
        $response = $this->post('/api/weather/city', [
            'temperature' => 23,
            'humidity' => '',
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => 1
        ]);

        $response->assertSessionHasErrors('humidity');
    }

    /** @test */
    public function weather_description_is_required()
    {
        $response = $this->post('/api/weather/city', [
            'temperature' => 23,
            'humidity' => 99,
            'weather_description' => '',
            'time' => '2022-01-01 12:00:00',
            'city_id' => 1
        ]);

        $response->assertSessionHasErrors('weather_description');
    }

    /** @test */
    public function temperature_is_valid()
    {
        $response = $this->post('/api/weather/city', [
            'temperature' => 'abc',
            'humidity' => 99,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => 1
        ]);

        $response->assertSessionHasErrors('temperature');
    }

    /** @test */
    public function humidity_is_valid()
    {
        $response = $this->post('/api/weather/city', [
            'weather' => 23,
            'humidity' => 'abc',
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => 1
        ]);

        $response->assertSessionHasErrors('humidity');
    }

    /** @test */
    public function the_city_weather_data_can_be_stored()
    {
        $cityWeatherDataCount = CityWeatherData::count();
        $response = $this->post('/api/weather/city', [
            'temperature' => 23,
            'humidity' => 90,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => 1
        ]);
        $cityWeatherData = CityWeatherData::orderBy('id','desc')->first();
        $response->assertStatus(302);
        $response->assertRedirect(route('city-weather.get', $cityWeatherData->id));
        $this->assertCount($cityWeatherDataCount + 1, CityWeatherData::all());
    }

    /** @test */
    public function the_city_weather_id_is_required_when_updating()
    {
        $response = $this->patch('/api/weather/city', [
            'id' => '',
            'temperature' => 23,
            'humidity' => 90,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => 1
        ]);

        $response->assertSessionHasErrors('id');
    }

    /** @test */
    public function the_city_weather_data_can_be_updated()
    {
        $this->post('/api/weather/city', [
            'temperature' => 23,
            'humidity' => 90,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => 1
        ]);
        $cityWeatherData = CityWeatherData::first();
        $cityWeatherDataCount = CityWeatherData::count();
        $response = $this->patch('/api/weather/city', [
            'id' => $cityWeatherData->id,
            'temperature' => 25,
            'humidity' => 95,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => 1
        ]);
        $cityWeatherData->refresh();
        $this->assertEquals(25, $cityWeatherData->temperature);
        $this->assertEquals(95, $cityWeatherData->humidity);
        $this->assertEquals('Mostly Cloudy', $cityWeatherData->weather_description);
        $this->assertEquals('2022-01-01 12:00:00', $cityWeatherData->time);
        $this->assertEquals(1, $cityWeatherData->city_id);
        $this->assertCount($cityWeatherDataCount, CityWeatherData::all());
        $response->assertStatus(302);
        $response->assertRedirect(route('city-weather.get', $cityWeatherData->id));
    }
}