<?php

namespace App\Documentation\Researcher\DTO;

use App\Documentation\Aspect\DTO\AspectIdDTO;

/**
 * @deprecated
 */
final readonly class FileDTO
{
    public function __construct(
        public string $lang,
        public string $product,
        public string $version,
        public string $page,
        public string $content,
    ) {
    }

    public function toAspectId(): AspectIdDTO
    {
        return new AspectIdDTO(
            lang: $this->lang,
            product: $this->product,
            version: $this->version,
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
