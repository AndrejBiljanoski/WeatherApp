<?php

namespace App\Http\Services;

use App\Builders\OpenWeatherBuilder;
use App\Http\Traits\ApiRequestTrait;
use Carbon\Carbon;

class OpenWeatherApiService
{
    use ApiRequestTrait;

    private const BASE_URL = 'https://api.openweathermap.org/data/2.5/weather';
    private $builder;

    public function __construct()
    {   
        $this->builder = new OpenWeatherBuilder();
    }
    public function get(float $lat, float $lon): array
    {
        $builder = $this->builder;
        $builder->addLang(ENV('OPEN_WEATHER_LANGUAGE'));
        $builder->addUnit(ENV('OPEN_WEATHER_UNIT'));
        $builder->addLat($lat)->addLon($lon);
        $url = SELF::BASE_URL . "?" . $builder->getURL();
        $data = $this->request('GET', $url);
        return $this->formatData($data);
    }

    private function formatData(string $data): array
    {
        $weatherData = json_decode($data, true);
        $weatherUnits = $weatherData['main'];
        $weatherDescription = $weatherData['weather'][0]['description'] ?? 'No Description Available';
        return [
            'temperature' => $weatherUnits['temp'],
            'humidity' => $weatherUnits['humidity'],
            'weather_description' => $weatherDescription,
            'time' => Carbon::parse($weatherData['dt'])->format('Y-m-d H:i:s')
        ]; 
    }
}
