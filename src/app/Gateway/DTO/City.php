<?php

namespace App\Gateway\DTO;

final readonly class City
{
    public function __construct(
        public string $title,
    ) {
    }
}
