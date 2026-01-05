<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Service;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use ShabuShabu\Uid\Facades\Uid;

class Rule implements ValidationRule
{
    public function __construct(
        protected ?string $class = null,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Uid::isValid($value, $this->class)) {
            $fail('uid::validation.invalid')->translate();
        }
    }
}
