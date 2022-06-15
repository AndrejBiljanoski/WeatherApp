<?php

namespace Database\Factories;

use App\Models\City;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityWeatherDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'temperature' => $this->faker->unique()->numberBetween(20,40),
            'humidity' => $this->faker->unique()->numberBetween(0,100),
            'city_id' => City::inRandomOrder()->first(),
            'weather_description' => $this->faker->sentence(),
            'time' => Carbon::now()->subMinutes(rand(1, 60))
        ];
    }
}
