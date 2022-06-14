<?php

namespace App\Console\Commands;

use App\Models\City;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FetchDataForSingleCity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'city:get {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cityId = $this->argument('id');
        $city = City::leftJoin('city_weather_data', 'city_weather_data.city_id', 'cities.id')
            ->where('cities.id', $cityId)
            ->orderBy('city_weather_data.id', 'DESC')
            ->get([
                'cities.id',
                'name',
                'latitude',
                'longitude',
                DB::raw('CONCAT(city_weather_data.temperature, " C")'),
                DB::raw('CONCAT (
                    city_weather_data.temperature 
                    -
                    (
                    SELECT cwd.temperature
                    FROM city_weather_data as cwd
                    WHERE cwd.city_id = cities.id
                    AND cwd.id < city_weather_data.id
                    ORDER BY cwd.id DESC
                    LIMIT 1
                ), " C") as temperature_trend'),
                DB::raw('CONCAT(city_weather_data.humidity, " %")'),
                DB::raw('CONCAT (
                    city_weather_data.humidity 
                    -
                    (
                    SELECT cwd.humidity
                    FROM city_weather_data as cwd
                    WHERE cwd.city_id = cities.id
                    AND cwd.id < city_weather_data.id
                    ORDER BY cwd.id DESC
                    LIMIT 1
                    ), " %") as humidity_trend'),
                'time'
        ])
        ->toArray();
        $this->table(
            ['Id', 'City Name', 'Latitude', 'Longitude', 'Temperature', 'Temperature Trend', 'Humidity', 'Humidity Trend', 'Measured At'],
            $city
        );
        return 0;
    }
}
