<?php

namespace App\Converter\Examples;

class DummyDTO
{
    public function __construct(
        public int $id,
        public string $content,
    ) {
    }
}
