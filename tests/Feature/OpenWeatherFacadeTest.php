<?php

namespace Tests\Feature;

use App\Facades\OpenWeather;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OpenWeatherFacadeTest extends TestCase
{
    /** @test */
    public function openweather_facade_returns_an_array()
    {
        $response = OpenWeather::get(35, 45);
        $this->assertIsArray($response);
    }

    /** @test */
    public function openweather_facade_return_array_has_valid_keys()
    {
        $response = OpenWeather::get(35, 45);
        $this->assertArrayHasKey('temperature', $response);
        $this->assertArrayHasKey('humidity', $response);
        $this->assertArrayHasKey('weather_description', $response);
        $this->assertArrayHasKey('time', $response);
    }

     /** @test */
    public function openweather_facade_return_array_keys_have_valid_value()
    {
        $response = OpenWeather::get(35, 45);
        $this->assertIsFloat($response['temperature']);
        $this->assertThat($response['humidity'], $this->logicalAnd(
            $this->isType('int'), 
            $this->greaterThanOrEqual(0)
        ));
        $this->assertIsString($response['weather_description']);
        $this->assertIsString($response['time']);
    }
}
