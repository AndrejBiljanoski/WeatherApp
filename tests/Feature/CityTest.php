<?php

namespace Tests\Feature;

use App\Helpers\CoordinateHelper;
use App\Models\City;
use App\Models\CityWeatherData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CityTest extends TestCase
{
    /** @test */
    public function all_cities_have_valid_coordinates()
    {
        $cities = City::cursor();
        foreach ($cities as $city) {
            $validLat = CoordinateHelper::validLatitude($city->latitude);
            $validLon = CoordinateHelper::validLongitude($city->longitude);
            $this->assertTrue($validLat && $validLon);
        }
    }

    /** @test */
    public function creating_a_city_with_invalid_latitude_creates_coordinates_90_180()
    {
        $newCity = City::create([
            'name' => 'Atlantis',
            'latitude' => 250.0,
            'longitude' => 21.3347
        ]);
        $this->assertEquals('Atlantis', $newCity->name);
        $this->assertEquals(90.00, $newCity->latitude);
        $this->assertEquals(180.00, $newCity->longitude);
    }

    /** @test */
    public function creating_a_city_with_invalid_longitude_creates_coordinates_90_180()
    {
        $newCity = City::create([
            'name' => 'Atlantis',
            'latitude' => 41.9833,
            'longitude' => 360
        ]);
        $this->assertEquals('Atlantis', $newCity->name);
        $this->assertEquals(90.00, $newCity->latitude);
        $this->assertEquals(180.00, $newCity->longitude);
    }

    /** @test */
    public function a_city_can_be_stored()
    {
        $citiesCount = City::count();
        $response = $this->post('/api/city', [
            'name' => 'Atlantis',
            'latitude' => 41.9833,
            'longitude' => 21.3347
        ]);
        $newCity = City::orderBy('id', 'desc')->first();
        $response->assertStatus(302);
        $response->assertRedirect(route('city.show', $newCity->id));
        $this->assertDatabaseHas('cities', $newCity->toArray());
        $this->assertCount($citiesCount + 1, City::all());
    }

    /** @test */
    public function the_store_city_method_validated_longitude()
    {
        $response = $this->post('/api/city', [
            'name' => 'Atlantis',
            'latitude' => 41.9833,
            'longitude' => 200.00
        ]);
        $response->assertSessionHasErrors('longitude');
    }

    /** @test */
    public function the_store_city_method_validated_latitude()
    {
        $response = $this->post('/api/city', [
            'name' => 'Atlantis',
            'latitude' => 410.9833,
            'longitude' => 21.3347
        ]);
        $response->assertSessionHasErrors('latitude');
    }

    /** @test */
    public function a_city_can_be_updated()
    {
        $newCity = City::factory(1)->create()->first();
        $citiesCount = City::count();
        $response = $this->patch('/api/city/' . $newCity->id, [
            'name' => 'Atlantis',
            'latitude' => 41.9833,
            'longitude' => 21.3347
        ]);
        $newCity->refresh();
        $response->assertStatus(302);
        $response->assertRedirect(route('city.show', $newCity->id));
        $this->assertDatabaseHas('cities', $newCity->toArray());
        $this->assertCount($citiesCount, City::all());
    }

    /** @test */
    public function the_update_city_method_validated_longitude()
    {
        $newCity = City::factory(1)->create()->first();
        $response = $this->patch('/api/city/' . $newCity->id, [
            'name' => 'Atlantis',
            'latitude' => 41.9833,
            'longitude' => 200.00
        ]);
        $response->assertSessionHasErrors('longitude');
    }

    /** @test */
    public function the_update_city_method_validated_latitude()
    {
        $newCity = City::factory(1)->create()->first();
        $response = $this->patch('/api/city/' . $newCity->id, [
            'name' => 'Atlantis',
            'latitude' => 400.9833,
            'longitude' => 21.3347
        ]);
        $response->assertSessionHasErrors('latitude');
    }

    /** @test */
    public function a_city_can_be_deleted()
    {
        $newCity = City::factory(1)->create()->first();
        $newCityData = $newCity->toArray();
        $citiesCount = City::count();
        $response = $this->delete('/api/city/' . $newCity->id);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('cities', $newCityData);
        $this->assertCount($citiesCount - 1, City::all());
    }

    /** @test */
    public function deleting_a_city_deletes_weather_data_for_the_city_as_well()
    {
        $newCity = City::factory(1)->create()->first();
        $newCityData = $newCity->toArray();
        $response = $this->post('/api/city-weather', [
            'temperature' => 23.0,
            'humidity' => 90.0,
            'weather_description' => 'Mostly Cloudy',
            'time' => '2022-01-01 12:00:00',
            'city_id' => $newCity->id
        ]);
        $newCityWeather = CityWeatherData::orderBy('id','desc')->first();
        $newCityWeatherData = $newCityWeather->toArray();
        $response = $this->delete('/api/city/' . $newCity->id);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('cities', $newCityData);
        $this->assertDatabaseMissing('city_weather_data', $newCityWeatherData);
    }
}
