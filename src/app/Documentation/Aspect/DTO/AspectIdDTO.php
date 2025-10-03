<?php

namespace App\Documentation\Aspect\DTO;

final readonly class AspectIdDTO implements \Stringable
{
    public function __construct(
        /** @example eng|rus */
        public string $lang,
        /** @example matlab */
        public string $product,
        /** @example .* */
        public string $version,
    ) {
    }

    public function match(self $aspectId): bool
    {
        return (bool) @preg_match($this->toRegexString(), $aspectId);
    }

    public function __toString(): string
    {
        return sprintf(
            '%s:%s:%s',
            $this->lang,
            $this->product,
            $this->version,
        );
    }

    public function toRegexString(): string
    {
        return sprintf(
            '$(%s):(%s):(%s)$',
            $this->lang,
            $this->product,
            $this->version,
        );
    }
}
