<?php

namespace App\Documentation\Aspect\DTO;

use Illuminate\Support\Collection;

/**
 * @mixin Collection
 */
final readonly class AspectMapperDTO
{
    public function __construct(
        /** @var AspectMapperItemDTO[] */
        private Collection $items,
    ) {
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->items, $name], $arguments);
    }
}
