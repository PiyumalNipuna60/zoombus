<?php

namespace App\Rules;

use App\SupportTickets;
use Hashids\Hashids;
use Illuminate\Contracts\Validation\Rule;

class HashidsRule implements Rule
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
        $decoded = (new Hashids('', 16))->decode($value)[0];
        return SupportTickets::whereId($decoded)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return \Lang::get('validation.verification_failed');
    }
}
