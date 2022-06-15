<?php

namespace Tests\Feature;

use App\Facades\OpenWeather;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

class OpenWeatherFacadeTest extends TestCase
{
    /** @test */
    public function openweather_facade_can_be_called()
    {
        OpenWeather::spy();
        OpenWeather::get(21.43, 41.98);
        OpenWeather::shouldHaveReceived('get')->once()->with(21.43, 41.98);
    }

    /** @test */
    public function openweather_facade_returns_an_array()
    {
        $response = OpenWeather::get(21.43, 41.98);
        $this->assertIsArray($response);
    }

    /** @test */
    public function openweather_facade_return_array_has_valid_keys()
    {
        $response = OpenWeather::get(21.43, 41.98);
        $this->assertArrayHasKey('temperature', $response);
        $this->assertArrayHasKey('humidity', $response);
        $this->assertArrayHasKey('weather_description', $response);
        $this->assertArrayHasKey('time', $response);
    }

    /** @test */
    public function openweather_facade_return_array_keys_have_valid_value()
    {
        $response = OpenWeather::get(21.43, 41.98);
        $this->assertIsFloat($response['temperature']);
        $this->assertThat($response['humidity'], $this->logicalAnd(
            $this->isType('int'),
            $this->greaterThanOrEqual(0)
        ));
        $this->assertIsString($response['weather_description']);
        $this->assertIsString($response['time']);
    }

    /** @test */
    public function openweather_facade_returns_null_if_coordinates_are_not_valid()
    {
        $response = OpenWeather::get(350, 450);
        $this->assertNull($response);
    }
}
