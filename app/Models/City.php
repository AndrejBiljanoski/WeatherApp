<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function weather_data()
    {
        return $this->hasMany(CityWeatherData::class, 'city_id');
    }
}
