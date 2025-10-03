<?php

namespace App\AsyncTask\Contracts;

interface HandlerClassStringContract
{
    public function parseHandlerString(string $handler): array;
    public function createHandlerString(string|object $handler, array $params = []): string;
}
