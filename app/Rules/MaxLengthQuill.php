<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxLengthQuill implements Rule
{
    private $max;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($max)
    {
        $this->max = $max;
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
        return strlen(strip_tags($value)) <= $this->max;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Le texte de :attribute ne peut contenir plus de ' . $this->max . ' caractères.';
    }
}
