<?php

namespace App\Gateway\Contracts;

interface RequestHeadersInterface
{
    public function getHeaders(): array;
}
