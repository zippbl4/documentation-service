<?php

namespace App\Gateway\DTO\Vacancies;

readonly class GetRecommendationVacanciesResponseObject
{
    public function __construct(
        public bool $result,
        public ?RecommendationVacancies $response,
        public mixed $errors,
    ) {
    }
}
