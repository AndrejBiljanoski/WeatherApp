<?php

namespace App\Helpers;

class CoordinateHelper {
    public static function validLongitude($longitude)
    {
        return preg_match('/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $longitude);
    } 
    public static function validLatitude($latitude)
    {
        return preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $latitude);
    } 
}