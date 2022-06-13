<?php

namespace App\Interfaces;

interface OpenWeatherBuilderInterface
{

    public function addUnit(string $unit): OpenWeatherBuilderInterface;

    public function addLang(string $lang): OpenWeatherBuilderInterface;

    public function addLat(string $lat): OpenWeatherBuilderInterface;

    public function addlon(string $lon): OpenWeatherBuilderInterface;

    public function getURL(): string;
}
