<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class IsAbleTo implements Rule
{
    private $user;
    private $ability;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($ability)
    {
        $this->ability = $ability;
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
        if (is_array($value)) {
            $users = [];
            foreach ($value as $user) {
                $user = User::findOrFail($user);
                $users[$user->full_name] = $user->isAbleTo($this->ability);
            }
            if (in_array(false, $users)) {
                foreach ($users as $username => $value) {
                    $this->user = $username;
                    return false;
                }
            }
            return true;
        }
        $this->user = User::findOrFail($value);
        return $this->user->isAbleTo($this->ability);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->user . " n'a pas les autorisations requises pour contrôler une agence";
    }
}