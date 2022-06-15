<?php

namespace Tests\Feature;

use App\Facades\OpenWeather;
use App\Jobs\StoreOpenWeatherDataJob;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class GetOpenWeatherDataCommandTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function openweather_get_command_is_executed_successfuly()
    {
        $this->artisan('db:seed');
        $this->artisan('openweather:get')->assertSuccessful()->assertExitCode(0);
    }

    /** @test */
    public function openweather_get_command_creates_jobs()
    {
        Queue::fake();
        $this->artisan('db:seed');
        $this->artisan('openweather:get');
        Queue::assertPushed(StoreOpenWeatherDataJob::class);
    }

    /** @test **/
    public function openweather_get_command_recieves_openweather_facade()
    {
        Queue::fake();
        OpenWeather::spy();
        $this->artisan('db:seed');
        $this->artisan('openweather:get');
        $cities = City::cursor();
        foreach ($cities as $city) {
            OpenWeather::shouldHaveReceived('get')->with($city->latitude, $city->longitude);
        }
    }

    /** @test */
    public function openweather_get_command_creates_vaild_jobs()
    {
        Queue::fake();
        $this->artisan('db:seed');
        $this->artisan('openweather:get');
        Queue::assertPushed(StoreOpenWeatherDataJob::class, function ($job) {
            $this->assertIsObject($job);
            $this->assertObjectHasAttribute('chunk', $job);
            $this->assertArrayHasKey('temperature', $job->chunk);
            $this->assertArrayHasKey('humidity', $job->chunk);
            $this->assertArrayHasKey('weather_description', $job->chunk);
            $this->assertArrayHasKey('time', $job->chunk);
            $this->assertArrayHasKey('city_id', $job->chunk);
            return $job;
        });
    }
}
