<?php

namespace App\Builders;

class OpenWeatherBuilder
{
    private string $unit = 'metric';
    private string $lang = 'en';
    private string $lat;
    private string $lon;

    public function __construct(float $lat, float $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }

    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }

    public function setLang(string $lang): void
    {
        $this->lang = $lang;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLon(): float
    {
        return $this->lon;
    }
}
