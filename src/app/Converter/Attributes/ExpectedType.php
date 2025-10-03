<?php

namespace App\Converter\Attributes;

use Attribute;
use InvalidArgumentException;

#[Attribute(Attribute::TARGET_PARAMETER)]
class ExpectedType
{
    public function __construct(public string $class) {}

    /**
     * @TODO
     * Проверяет, соответствует ли объект ожидаемому типу.
     *
     * @param object $source Объект для проверки.
     * @return void
     * @throws InvalidArgumentException Если объект не соответствует ожидаемому типу.
     */
    public function validate(object $source): void {
        if (!$source instanceof $this->class) {
            throw new InvalidArgumentException(
                'Invalid source type: expected ' . $this->class . ', got ' . $source::class
            );
        }
    }
}
