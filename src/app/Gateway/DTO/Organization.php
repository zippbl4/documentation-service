<?php

namespace App\Gateway\DTO;

readonly class Organization
{
    public function __construct(
        public string $title,
        public ?string $icon,
    ) {
    }
}
