<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class InArrayRule implements Rule
{
    protected $array;

    /**
     * Create a new rule instance.
     *
     * @param array $array
     */
    public function __construct($array = [])
    {
        $this->array = $array;
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
        if($this->array && !in_array($value, $this->array)){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $params = implode(', ',$this->array);
        return ":attribute должен быть равен одному из вариантов: [$params]";
    }
}
