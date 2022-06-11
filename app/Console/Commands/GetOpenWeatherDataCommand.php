<?php

namespace App\Console\Commands;

use App\Builders\OpenWeatherBuilder;
use App\Facades\OpenWeather;
use App\Models\City;
use App\Models\CityWeatherData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetOpenWeatherDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openweather:get';

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
        $cities = City::all();
        $dataChunk = [];
        foreach ($cities as $city) {
            $openWeather = new OpenWeatherBuilder($city->latitude, $city->longitude);
            $openWeatherData = OpenWeather::get($openWeather);
            $openWeatherData['city_id'] = $city->id;
            $dataChunk[] = $openWeatherData;
        }
        $dataChunk = collect($dataChunk)->chunk(20);
        foreach ($dataChunk as $chunk) {
            CityWeatherData::insert($chunk->toArray());
        }
        return 0;
    }
}
