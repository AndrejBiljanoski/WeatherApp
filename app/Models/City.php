<?php

namespace App\Models;

use App\Helpers\CoordinateHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class City extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $valid = true;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

    public function weather_data()
    {
        return $this->hasMany(CityWeatherData::class, 'city_id');
    }

    public function setLatitudeAttribute($latitude)
    {
        $validLatitude = CoordinateHelper::validLatitude($latitude);
        if (!$validLatitude || !$this->valid) {
            $this->resetLatLon('Latitude', $latitude);
        } else {
            $this->attributes['latitude'] = $latitude;
        }
    }

    public function setLongitudeAttribute($longitude)
    {
        $validLongitude = CoordinateHelper::validLongitude($longitude);
        if (!$validLongitude || !$this->valid) {
            $this->resetLatLon('Longitude', $longitude);
        } else {
            $this->attributes['longitude'] = $longitude;
        }
    }

    private function resetLatLon(string $type, string $val): void
    {
        Log::error("Invalid $type parameter recieved for " . $this->attributes['name'] . " ($val)");
        $this->attributes['latitude'] = 90.00;
        $this->attributes['longitude'] = 180.00;
        $this->valid = false;
    }

    public function getCurrentWeather()
    {
        return $this->weather_data->last();
    }
}
