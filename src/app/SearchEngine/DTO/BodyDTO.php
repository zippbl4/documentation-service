<?php

namespace App\SearchEngine\DTO;

final readonly class BodyDTO
{
    public function __construct(
        public string $title,
        public string $body,
    ) {
    }
}
