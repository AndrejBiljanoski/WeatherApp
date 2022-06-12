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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Add the url parameter id into the validation data
        if($this->method() == 'PATCH')
            $this->mergeIfMissing(['id' => $this->id]);
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
            'id' => 'sometimes|numeric|exists:city_weather_data,id',
            'weather_description' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'time' => 'required'
        ];
    }
}
