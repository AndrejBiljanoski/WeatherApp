<?php

namespace App\Rules;

use App\Helpers\CoordinateHelper;
use Illuminate\Contracts\Validation\Rule;

class LongitudeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return CoordinateHelper::validLongitude($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attrbute must be a valid longitude value.';
    }
}
