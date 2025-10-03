<?php

namespace App\Gateway\DTO;

use App\Gateway\Contracts\ResponseInterface;

readonly class Response implements ResponseInterface
{
    public function __construct(
        private int    $statusCode,
        private string $response,
    )
    {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponse(): string
    {
        return $this->response;
    }
}
