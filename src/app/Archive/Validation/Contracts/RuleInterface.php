<?php

namespace App\Archive\Validation\Contracts;

use App\Archive\Validation\DTO\ContextDTO;
use App\Archive\Validation\Exceptions\ValidatorException;

interface RuleInterface
{
    /**
     * @param ContextDTO $context
     * @param \Closure $fail
     * @return void
     * @throws ValidatorException
     */
    public function validateWithContext(ContextDTO $context, \Closure $fail): void;
}
