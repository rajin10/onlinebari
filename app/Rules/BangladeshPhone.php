<?php

namespace App\Rules;

use App\Support\BdPhone;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BangladeshPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) && ! is_numeric($value)) {
            $fail('সঠিক মোবাইল নম্বর দিন।');

            return;
        }

        if (BdPhone::normalize((string) $value) === null) {
            $fail('সঠিক বাংলাদেশি মোবাইল নম্বর দিন (যেমনঃ 01XXXXXXXXX)।');
        }
    }
}
