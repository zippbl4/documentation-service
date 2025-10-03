<?php

namespace App\Gateway\DTO\Vacancies;

use App\Gateway\DTO\Vacancy;

readonly class RecommendationVacancies
{
    public function __construct(
        /** @var Vacancy[] */
        public array $vacancies,
        public int $personal_count,
        public int $similar_count,
    ) {
    }
}
