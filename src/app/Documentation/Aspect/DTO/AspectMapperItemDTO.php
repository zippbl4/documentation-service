<?php

namespace App\Documentation\Aspect\DTO;

final readonly class AspectMapperItemDTO implements \Stringable
{
    public function __construct(
        /** @example {version} */
        private string $pattern,
        /** @example ru */
        private string $from,
        /** @example rus */
        private string $to,
    ) {
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }


    public function __toString(): string
    {
        return sprintf(
            '%s:%s:%s',
            $this->pattern,
            $this->from,
            $this->to,
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
