<?php

namespace App\Page\Decorator\Factories;

use App\Page\Decorator\Contracts\BaseDecorator;

interface DecoratorFactoryInterface
{
    public function getDecorator(string $name): BaseDecorator;

    /**
     * @param list<string> $names
     * @return BaseDecorator[]
     */
    public function getDecorators(array $names): array;
}
