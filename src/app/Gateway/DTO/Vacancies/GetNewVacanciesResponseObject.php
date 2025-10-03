<?php

namespace App\Gateway\DTO\Vacancies;

readonly class GetNewVacanciesResponseObject
{
    public function __construct(
        public bool $result,
        public ?NewVacancies $response,
        public mixed $errors,
    ) {
    }
}
