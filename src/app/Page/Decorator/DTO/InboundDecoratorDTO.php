<?php

namespace App\Page\Decorator\DTO;

class InboundDecoratorDTO
{
    public function __construct(
        public string $name,
        public ?string $userCustomTemplate,
    ) {
    }
}
