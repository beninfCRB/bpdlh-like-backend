<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TahapanSalurValidation implements Rule
{
    private $uhuy;
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
        //
        $total = 0;
        foreach ($value as $key => $value) {
            $total += (int) $value;
        }

        if ($total < 100 || $total > 100) {
            # code...
            return false;
        } else {
            # code...
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The array values must all be exactly 100.';
    }
}
