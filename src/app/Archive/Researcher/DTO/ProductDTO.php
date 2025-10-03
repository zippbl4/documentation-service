<?php

namespace App\Archive\Researcher\DTO;

class ProductDTO
{
    public function __construct(
        public string $lang,
        public string $product,
        public string $version,
    ) {
    }
}
