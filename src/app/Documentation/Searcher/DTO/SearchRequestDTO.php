<?php

namespace App\Documentation\Searcher\DTO;

final readonly class SearchRequestDTO
{
    public function __construct(
        public string $query,
    ) {
    }
}
