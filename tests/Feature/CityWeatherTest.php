<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\CityWeatherData;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CityWeatherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function temperature_is_required()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create'],
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token->token,
            'Accept' => 'application/json'
        ])->post('/api/city-weather', [
            'temperature' => '',
            'humidity' => 99,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => City::factory()->create()->id
        ]);

        $response->assertJson([
            'message' => "The given data was invalid.",
            'errors' => [
                "temperature" => ["The temperature field is required."]
            ]
        ]);
    }

    /** @test */
    public function humidity_is_required()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create'],
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token->token,
            'Accept' => 'application/json'
        ])->post('/api/city-weather', [
            'temperature' => 23,
            'humidity' => '',
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => City::factory()->create()->id
        ]);

        $response->assertJson([
            'message' => "The given data was invalid.",
            'errors' => [
                "humidity" => ["The humidity field is required."]
            ]
        ]);
    }

    /** @test */
    public function weather_description_is_required()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create'],
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token->token,
            'Accept' => 'application/json'
        ])->post('/api/city-weather', [
            'temperature' => 23,
            'humidity' => 99,
            'weather_description' => '',
            'time' => '2022-01-01 12:00:00',
            'city_id' => City::factory()->create()->id
        ]);

        $response->assertJson([
            'message' => "The given data was invalid.",
            'errors' => [
                "weather_description" => ["The weather description field is required."]
            ]
        ]);
    }

    /** @test */
    public function temperature_is_being_validated()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create'],
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token->token,
            'Accept' => 'application/json'
        ])->post('/api/city-weather', [
            'temperature' => 'abc',
            'humidity' => 99,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => City::factory()->create()->id
        ]);

        $response->assertJson([
            'message' => "The given data was invalid.",
            'errors' => [
                "temperature" => ["The temperature must be a number."]
            ]
        ]);
    }

    /** @test */
    public function humidity_is_being_validated()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create'],
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token->token,
            'Accept' => 'application/json'
        ])->post('/api/city-weather', [
            'temperature' => 23,
            'humidity' => 'abc',
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => City::factory()->create()->id
        ]);

        $response->assertJson([
            'message' => "The given data was invalid.",
            'errors' => [
                "humidity" => ["The humidity must be a number."]
            ]
        ]);
    }

    /** @test */
    public function the_city_weather_data_can_be_stored()
    {
        $cityWeatherDataCount = CityWeatherData::count();
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create'],
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token->token,
            'Accept' => 'application/json'
        ])->post('/api/city-weather', [
            'temperature' => 23,
            'humidity' => 90,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => City::factory()->create()->id
        ]);
        $cityWeatherData = CityWeatherData::orderBy('id', 'desc')->first();
        $response->assertStatus(302);
        $response->assertRedirect(route('city-weather.show', $cityWeatherData->id));
        $this->assertCount($cityWeatherDataCount + 1, CityWeatherData::all());
    }

    /** @test */
    public function the_city_weather_data_can_be_updated()
    {
        $city = City::factory()->create();
        $cityWeatherData = CityWeatherData::factory()->create()->first();
        $cityWeatherDataCount = CityWeatherData::count();
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['edit'],
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token->token,
            'Accept' => 'application/json'
        ])->patch('/api/city-weather/' . $cityWeatherData->id, [
            'id' => $cityWeatherData->id,
            'temperature' => 25,
            'humidity' => 95,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => $city->id
        ]);
        $cityWeatherData->refresh();
        $this->assertEquals(25, $cityWeatherData->temperature);
        $this->assertEquals(95, $cityWeatherData->humidity);
        $this->assertEquals('Mostly Cloudy', $cityWeatherData->weather_description);
        $this->assertEquals('2022-01-01 12:00:00', $cityWeatherData->time);
        $this->assertEquals($city->id, $cityWeatherData->city_id);
        $this->assertCount($cityWeatherDataCount, CityWeatherData::all());
        $response->assertStatus(302);
        $response->assertRedirect(route('city-weather.show', $cityWeatherData->id));
    }

    /** @test */
    public function the_city_weather_data_can_be_deleted()
    {
        $cityWeatherDataCount = CityWeatherData::count();
        $cityWeatherData = CityWeatherData::factory()->for(
            City::factory()
        )->create()->first();
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['delete'],
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token->token,
            'Accept' => 'application/json'
        ])->delete('/api/city-weather/' . $cityWeatherData->id);
        $this->assertCount($cityWeatherDataCount, CityWeatherData::all());
        $this->assertNull(CityWeatherData::find($cityWeatherData->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function the_city_weather_data_is_returned_in_correct_format()
    {
        $cityWeatherData = CityWeatherData::factory()->for(
            City::factory()
        )->create()->first();
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['get'],
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token->token,
            'Accept' => 'application/json'
        ])->get('/api/city-weather/' . $cityWeatherData->id);
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
        $cityWeatherData = CityWeatherData::factory()->for(
            City::factory()
        )->create()->first();
        $cityWeatherData->temperature = 23;
        $cityWeatherData->save();
        $this->assertEquals($cityWeatherData->getKelvin(), 296.15);
    }

    /** @test */
    public function city_weather_data_conversions_to_degrees_are_valid()
    {
        $cityWeatherData = CityWeatherData::factory()->for(
            City::factory()
        )->create()->first();
        $cityWeatherData->temperature = 23;
        $cityWeatherData->save();
        $this->assertEquals($cityWeatherData->getDegrees(), 73.40);
    }
}
