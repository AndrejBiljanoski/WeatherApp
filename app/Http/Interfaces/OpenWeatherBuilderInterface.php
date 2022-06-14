<?php

namespace App\Interfaces;

interface OpenWeatherBuilderInterface
{

    public function addUnit(string $unit): OpenWeatherBuilderInterface;

    public function addLang(string $lang): OpenWeatherBuilderInterface;

    public function addLat(float $lat): OpenWeatherBuilderInterface;

    public function addlon(float $lon): OpenWeatherBuilderInterface;

    public function getURL(): string;
}
