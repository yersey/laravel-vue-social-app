<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Validation\ValidationRule;

class StorageFileExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Storage::exists($value)) {
            $fail(':attribute file doesn\'t exist');
        }
    }
}
