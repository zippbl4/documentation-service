<?php

namespace App\SearchEngine\DTO;

final readonly class IndexDTO
{
    public function __construct(
        public string $lang,
        public string $product,
        public string $version,
        public string $page,
        public string $content,
    ) {
    }
}
