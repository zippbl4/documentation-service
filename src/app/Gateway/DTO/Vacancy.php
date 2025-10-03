<?php

namespace App\Gateway\DTO;

readonly class Vacancy
{
    public function __construct(
        public string $slug,
        public string $title,
        public string $small_description,
        public ?Organization $organization,
        public ?City $city,
    ) {
    }
}
