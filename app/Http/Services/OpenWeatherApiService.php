<?php

namespace App\Http\Services;

use App\Http\Traits\ApiRequestTrait;

class OpenWeatherApiService
{
    use ApiRequestTrait;

    private string $baseURL = 'https://api.openweathermap.org/data/2.5/weather';
    private string $unit;
    private string $lang;

    public function __construct($unit = 'metric', $lang = 'en')
    {
        $this->unit = $unit;
        $this->lang = $lang;
    }

    public function getByLatLon(array $params)
    {
        $url = $this->prepareURL($params);
        return $this->request('GET', $url);
    }

    private function prepareURL(array $params): string
    {
        $urlParams = http_build_query([
            'appid' => ENV('OPEN_WEATHER_API_KEY'),
            'lat' => $params['lat'] ?? '',
            'lon' => $params['lon'] ?? '',
            'unit' => $this->unit,
            'lang' => $this->lang
        ]);
        return $this->baseURL . "?$urlParams";
    }
}
