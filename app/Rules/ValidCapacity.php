<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCapacity implements Rule
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
        return (
            ($value >= config('consts.course.capacity_min')) &&
            ($value <= config('consts.course.capacity_max'))
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be between ' .
            config('consts.course.capacity_min') .
            ' and ' .
            config('consts.course.capacity_max');
    }
}
