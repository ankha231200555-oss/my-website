<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoForbiddenWords implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $forbidden = ['test', 'spam', 'xxx'];
        $lower = mb_strtolower((string) $value, 'UTF-8');

        foreach ($forbidden as $w) {
            if (str_contains($lower, $w)) {
                $fail('Trường :attribute chứa từ không được phép: "'.$w.'".');
            }
        }
    }
}
