<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ArrayOneNotNull implements Rule
{
    /**
     * Rule passes if one item in the array is not null.
     *
     * @param  string  $attribute
     * @param  mixed  $array
     * @return bool
     */
    public function passes($attribute, $array)
    {
        foreach ($array as $item) {
            if (!is_null($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please input at least one :attribute.';
    }
}
