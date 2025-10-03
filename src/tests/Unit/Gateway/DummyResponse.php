<?php

namespace Tests\Unit\Gateway;


class DummyResponse
{
    public function __construct(
        private readonly bool $result,
        private readonly array $response,
        private readonly mixed $errors,
    )
    {
    }
}
