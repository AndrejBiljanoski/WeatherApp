<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityWeatherManageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'temperature' => 'required|numeric|between:-100,100',
            'humidity' => 'required|numeric|between:0,99.99',
            'weather_description' => 'required|string',
            'time' => 'required'
        ];
    }
}
