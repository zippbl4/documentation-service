<?php

namespace App\Page\Decorator\Factories;

use App\Page\Decorator\Contracts\BaseDecorator;
use App\Page\Decorator\Contracts\HasUserInputDecoratorInterface;
use App\Page\Decorator\Contracts\SupportedDecoratorsInterface;

final class DecoratorFactory implements DecoratorFactoryInterface, SupportedDecoratorsInterface
{
    /**
     * @var list<string, BaseDecorator>
     */
    private array $decorators = [];

    public function addDecorator(BaseDecorator $decorator): void
    {
        $this->decorators[$decorator::getName()] = $decorator;
    }

    public function getDecorator(string $name): BaseDecorator
    {
        if (!isset($this->decorators[$name])) {
            throw new \InvalidArgumentException(
                "No decorator '{$name}'"
            );
        }

        return $this->decorators[$name];
    }

    public function getDecorators(array $names): array
    {
        $decorators = [];

        foreach ($names as $decorator) {
            $decorators[] = $this->getDecorator($decorator);
        }

        return $decorators;
    }

    public function getSupportedDecorators(): array
    {
        return array_keys($this->decorators);
    }

    public function hasUserInput(string $name): bool
    {
        return $this->getDecorator($name) instanceof HasUserInputDecoratorInterface;
    }
}
