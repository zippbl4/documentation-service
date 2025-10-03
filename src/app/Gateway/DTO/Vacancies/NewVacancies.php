<?php

namespace App\Gateway\DTO\Vacancies;

use App\Gateway\DTO\Vacancy;

readonly class NewVacancies
{
    public function __construct(
        /** @var Vacancy[] */
        public array $vacancies,
        public bool $personal,
    ) {
    }
}
