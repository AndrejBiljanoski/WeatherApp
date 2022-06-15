<?php

namespace App\Console\Commands;

use App\Facades\OpenWeather;
use App\Jobs\StoreOpenWeatherDataJob;
use App\Models\City;
use Illuminate\Console\Command;

class GetOpenWeatherDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openweather:get {--id=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to get data from Open Weather API. Creates jobs for inserting data.
    Can take parameters city id: ex. openweather:get --id=1 --id=2 for updating specific cities.';

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
        $option = $this->option('id');
        City::where(function ($query) use ($option) {
            if ($option) 
                $query->whereIn('id', $option);
        })->chunk(ENV('CHUNK_SIZE'), function($cities) {
            foreach ($cities as $city) {
                $openWeatherData = OpenWeather::get($city->latitude, $city->longitude);
                $openWeatherData['city_id'] = $city->id;
                StoreOpenWeatherDataJob::dispatch($openWeatherData);
            }
        });
        return 0;
    }
}