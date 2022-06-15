<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityWeatherData extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'temperature',
        'humidity',
        'weather_description',
        'time'
    ];

    protected $hidden = [
        'id'
    ];

    public $timestamps = false;

    public function setTimeAttribute($time)
    {
        $this->attributes['time'] = Carbon::parse($time)->format('Y-m-d H:i:s');
    }

    public function getTimeAttribute()
    {
        return Carbon::parse($this->attributes['time']);
    }

    public function getTemperatureAttribute()
    {
        return number_format($this->attributes['temperature'], 2);
    }

    public function getHumidityAttribute()
    {
        return number_format($this->attributes['humidity'], 2);
    }

    public function getKelvin()
    {
        return $this->temperature + 273.15;
    }

    public function getDegrees()
    {
        return $this->temperature * 1.8 + 32;
    }

    public function getWeatherTrend()
    {
        $prevData = CityWeatherData::where([
            ['id', '<', $this->id],
            ['city_id', $this->city_id]
        ])
        ->orderBy('id', 'desc')
        ->first();
        return [
            'temperature' => number_format($this->temperature - $prevData->temperature, 2),
            'humidity' => number_format($this->humidity - $prevData->humidity, 2)
        ];
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
