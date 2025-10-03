<?php

namespace App\Documentation\Viewer\DTO;

use App\Documentation\Aspect\DTO\AspectIdDTO;

readonly class PageRequestDTO implements \Stringable
{
    public function __construct(
        public string $lang,
        public string $product,
        public string $version,
        public string $page,
        public string $extension,
    ) {
    }

    public function getAspectId(): AspectIdDTO
    {
        return new AspectIdDTO(
            lang:    $this->lang,
            product: $this->product,
            version: $this->version,
        );
    }

    public function getPageWithExtension(): string
    {
        $page = $this->page;
        $page .= !empty($this->extension) ? ".$this->extension" : '';
        return $page;
    }

    public function __toString(): string
    {
        return implode(':', get_object_vars($this));
    }
}
