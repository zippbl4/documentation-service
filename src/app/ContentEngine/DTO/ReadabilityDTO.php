<?php

namespace App\ContentEngine\DTO;

class ReadabilityDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content,
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
