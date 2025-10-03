<?php

namespace App\Gateway\Endpoints;

use App\Gateway\Contracts\RequestInterface;
use App\Gateway\Contracts\RequestQueryInterface;

readonly class GetNewVacanciesRequestObject implements RequestInterface, RequestQueryInterface
{
    public function __construct(
        private string $uuid,
    ) {
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getUrl(): string
    {
        return 'api/vacancies/check-new/';
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
