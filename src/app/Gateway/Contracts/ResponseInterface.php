<?php

namespace App\Gateway\Contracts;

interface ResponseInterface
{
    public function getStatusCode(): int;
    public function getResponse(): string;
}
