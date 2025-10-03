<?php

namespace App\Context;

final class Context
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
