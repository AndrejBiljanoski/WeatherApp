<?php

namespace App\Builders;
use App\Interfaces\OpenWeatherBuilderInterface;

class OpenWeatherBuilder implements OpenWeatherBuilderInterface
{
    private string $unit;
    private string $lang;
    private float $lat;
    private float $lon;

    public function addUnit(string $unit): OpenWeatherBuilderInterface
    {
        $this->unit = $unit;
        return $this;
    }

    public function addLang(string $lang): OpenWeatherBuilderInterface
    {
        $this->lang = $lang;
        return $this;
    }

    public function addLat(string $lat): OpenWeatherBuilderInterface
    {
        $this->lat = $lat;
        return $this;
    }

    public function addlon(string $lon): OpenWeatherBuilderInterface
    {
        $this->lon = $lon;
        return $this;
    }

    public function getURL(): string
    {
        return http_build_query([
            'appid' => ENV('OPEN_WEATHER_API_KEY'),
            'lat' => $this->lat,
            'lon' => $this->lon,
            'units' => $this->unit,
            'lang' => $this->lang
        ]);
    }
}
