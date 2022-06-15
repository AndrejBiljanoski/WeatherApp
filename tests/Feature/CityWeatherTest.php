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
        $response = $this->post('/api/city-weather', [
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
        $response = $this->post('/api/city-weather', [
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
        $response = $this->post('/api/city-weather', [
            'temperature' => 23,
            'humidity' => 99,
            'weather_description' => '',
            'time' => '2022-01-01 12:00:00',
            'city_id' => 1
        ]);

        $response->assertSessionHasErrors('weather_description');
    }


    /** @test */
    public function city_weather_id_is_required_when_updating()
    {
        $response = $this->patch('/api/city-weather/abc', [
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
    public function city_weather_id_is_required_when_deleting()
    {
        $response = $this->delete('/api/city-weather/abc');
        $response->assertStatus(204);
    }

    /** @test */
    public function temperature_is_being_validated()
    {
        $response = $this->post('/api/city-weather', [
            'temperature' => 'abc',
            'humidity' => 99,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => 1
        ]);

        $response->assertSessionHasErrors('temperature');
    }

    /** @test */
    public function humidity_is_being_validated()
    {
        $response = $this->post('/api/city-weather', [
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
        $response = $this->post('/api/city-weather', [
            'temperature' => 23,
            'humidity' => 90,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => 1
        ]);
        $cityWeatherData = CityWeatherData::orderBy('id', 'desc')->first();
        $response->assertStatus(302);
        $response->assertRedirect(route('city-weather.show', $cityWeatherData->id));
        $this->assertCount($cityWeatherDataCount + 1, CityWeatherData::all());
    }

    /** @test */
    public function the_city_weather_data_can_be_updated()
    {
        $cityWeatherData = CityWeatherData::factory()->create()->first();
        $cityWeatherDataCount = CityWeatherData::count();
        $response = $this->patch('/api/city-weather/' . $cityWeatherData->id, [
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
        $response->assertRedirect(route('city-weather.show', $cityWeatherData->id));
    }

    /** @test */
    public function the_city_weather_data_can_be_deleted()
    {
        $cityWeatherDataCount = CityWeatherData::count();
        $cityWeatherData = CityWeatherData::factory()->create()->first();
        $response = $this->delete('/api/city-weather/' . $cityWeatherData->id);
        $this->assertCount($cityWeatherDataCount, CityWeatherData::all());
        $this->assertNull(CityWeatherData::find($cityWeatherData->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function the_city_weather_data_is_returned_in_correct_format()
    {
        $cityWeatherData = CityWeatherData::factory()->create()->first();
        $response = $this->get('/api/city-weather/' . $cityWeatherData->id);
        $response->assertStatus(200);
        $response->assertJson([
            "temperature" => $cityWeatherData->temperature,
            "humidity" => $cityWeatherData->humidity,
            "weather_description" => $cityWeatherData->weather_description,
            "city_id" => $cityWeatherData->city_id,
            "time" => $cityWeatherData->time,
            "city" => [
                "id" => $cityWeatherData->city->id,
                "name" => $cityWeatherData->city->name,
                "longitude" => $cityWeatherData->city->longitude,
                "latitude" => $cityWeatherData->city->latitude
            ]
        ]);
    }

    /** @test */
    public function city_weather_data_conversions_to_kelvin_are_valid()
    {
        $cityWeatherData = CityWeatherData::factory()->create()->first();
        $cityWeatherData->temperature = 23;
        $cityWeatherData->save();
        $this->assertEquals($cityWeatherData->getKelvin(), 296.15);
    }

    /** @test */
    public function city_weather_data_conversions_to_degrees_are_valid()
    {
        $cityWeatherData = CityWeatherData::factory()->create()->first();
        $cityWeatherData->temperature = 23;
        $cityWeatherData->save();
        $this->assertEquals($cityWeatherData->getDegrees(), 73.40);
    }
}
