<?php

namespace App\SearchEngine\DTO;

final readonly class SearchDTO
{
    public function __construct(
        public string $query,
    ) {
    }
}
