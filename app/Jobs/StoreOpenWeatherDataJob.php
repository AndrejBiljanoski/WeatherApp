<?php

namespace App\Jobs;

use App\Models\CityWeatherData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreOpenWeatherDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $chunk;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chunk)
    {
        $this->chunk = $chunk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        CityWeatherData::insert($this->chunk);
    }
}
