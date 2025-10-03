<?php

namespace App\Archive\Validation\DTO;

use App\Context\Context;

/**
 * @deprecated
 *
 * @use Context
 */
class ContextDTO
{
    private array $values = [];

    public function withValue(string $key, mixed $val): self
    {
        $this->values[$key] = $val;
        return $this;
    }

    public function value(string $key): mixed
    {
        return $this->values[$key];
    }
}
