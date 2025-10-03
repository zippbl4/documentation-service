<?php

namespace App\Documentation\Viewer\DTO;

readonly class WikiRequestDTO extends PageRequestDTO implements \Stringable
{
    public function __construct(
        string $lang,
        string $product,
        string $space,
        string $page,
        string $extension
    ) {
        parent::__construct($lang, $product, $space, $page, $extension);
    }
}
