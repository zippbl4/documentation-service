<?php

namespace App\Documentation\Aspect\DTO;

final readonly class AspectDecoratorItemDTO
{
    public function __construct(
        private string  $name,
        private ?string $userCustomTemplate,
    ) {
    }

    public function getDecoratorName(): string
    {
        return $this->name;
    }

    public function getUserCustomTemplate(): ?string
    {
        return $this->userCustomTemplate;
    }
}
