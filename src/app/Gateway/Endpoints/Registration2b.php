<?php

namespace App\Gateway\Endpoints;

use App\Gateway\Contracts\RequestInterface;
use App\Gateway\Contracts\RequestQueryInterface;

readonly class Registration2b implements RequestInterface, RequestQueryInterface
{
    public function __construct(
        private string $email,
        private string $password,
    ) {
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getUrl(): string
    {
        return 'api/auth/registration/2b';
    }

    public function getQueryParameters(): array
    {
        return get_object_vars($this);
    }

    public function __toString(): string
    {
        return sprintf(
            "\nUrl: %s\nMethod: %s\nQuery: %s",
            $this->getUrl(),
            $this->getMethod(),
            implode(', ', $this->getQueryParameters()),
        );
    }
}
