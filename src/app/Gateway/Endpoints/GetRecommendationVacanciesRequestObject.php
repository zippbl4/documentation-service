<?php

namespace App\Gateway\Endpoints;

use App\Gateway\Contracts\RequestInterface;
use App\Gateway\Contracts\RequestQueryInterface;

readonly class GetRecommendationVacanciesRequestObject implements RequestInterface, RequestQueryInterface
{
    public function __construct(
        private int   $limit,
        private array $spheres,
        private array $tags,
    ) {
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getUrl(): string
    {
        return 'api/vacancies/recommendations/';
    }

    public function getQueryParameters(): array
    {
        return get_object_vars($this);
    }

    public function __toString(): string
    {
        ;

        return sprintf(
            "\nUrl: %s\nMethod: %s\nQuery: %s",
            $this->getUrl(),
            $this->getMethod(),
            json_encode($this->getQueryParameters(), flags: JSON_PRETTY_PRINT),
        );
    }
}
