<?php

namespace App\Gateway\Contracts;

interface RequestQueryInterface
{
    public function getQueryParameters(): array;
}
