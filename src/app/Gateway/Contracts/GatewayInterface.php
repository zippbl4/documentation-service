<?php

namespace App\Gateway\Contracts;

use App\Gateway\DTO\Vacancies\GetNewVacanciesResponseObject;
use App\Gateway\DTO\Vacancies\GetRecommendationVacanciesResponseObject;

interface GatewayInterface
{
    public function getNewVacancies(string $uuid): GetNewVacanciesResponseObject;

    public function getRecommendationVacancies(
        array $spheres = [],
        array $tags = []
    ): GetRecommendationVacanciesResponseObject;
}
