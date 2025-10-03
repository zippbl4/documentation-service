<?php

namespace App\Page\Decorator\Contracts;

interface SupportedDecoratorsInterface
{
    public function getSupportedDecorators(): array;

    public function hasUserInput(string $name): bool;
}
