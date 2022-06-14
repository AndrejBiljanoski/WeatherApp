<?php

namespace App\Http\Requests;

use App\Rules\LatitudeRule;
use App\Rules\LongitudeRule;
use Illuminate\Foundation\Http\FormRequest;

class CityManageRequest extends FormRequest
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
            'name' => ['required','string'],
            'longitude' => ['required', 'numeric', new LongitudeRule],
            'latitude' => ['required', 'numeric', new LatitudeRule]
        ];
    }
}
