<?php

namespace App\Http\Services;

use App\Builders\OpenWeatherBuilder;
use App\Http\Traits\ApiRequestTrait;
use Carbon\Carbon;

class OpenWeatherApiService
{
    use ApiRequestTrait;

    private const BASE_URL = 'https://api.openweathermap.org/data/2.5/weather';

    public function get(OpenWeatherBuilder $openWeather): array
    {
        $url = $this->prepareURL($openWeather);
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

    private function prepareURL(OpenWeatherBuilder $openWeather): string
    {
        $urlParams = http_build_query([
            'appid' => ENV('OPEN_WEATHER_API_KEY'),
            'lat' => $openWeather->getLat(),
            'lon' => $openWeather->getLon(),
            'units' => $openWeather->getUnit(),
            'lang' => $openWeather->getLang()
        ]);
        return self::BASE_URL . "?$urlParams";
    }
}
