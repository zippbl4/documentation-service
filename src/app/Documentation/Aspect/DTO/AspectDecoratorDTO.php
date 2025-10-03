<?php

namespace App\Documentation\Aspect\DTO;

use Illuminate\Support\Collection;

/**
 * @mixin Collection
 */
final readonly class AspectDecoratorDTO
{
    public function __construct(
        /** @var AspectDecoratorItemDTO[] */
        private Collection $items,
    ) {
    }

    public function getNames(): array
    {
        return $this
            ->items
            ->map(fn(AspectDecoratorItemDTO $item): string => $item->getDecoratorName())
            ->toArray();
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->items, $name], $arguments);
    }
}
