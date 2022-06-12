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
}
