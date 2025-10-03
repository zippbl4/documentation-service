<?php

namespace App\Converter\Examples;

class FooDTO
{
    public function __construct(
        public int $id,
        public string $content,
    ) {
    }
}
