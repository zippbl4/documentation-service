<?php

namespace App\Gateway\Contracts;

interface RequestBodyInterface
{
    public function getRequestBody(): array;
}
