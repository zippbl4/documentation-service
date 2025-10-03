<?php

namespace App\Page\Decorator\Contracts;

use App\Context\Context;
use App\Page\Decorator\DTO\InboundDecoratorDTO;

interface ContentDecorationInterface
{
    /**
     * @param string $content
     * @param list<InboundDecoratorDTO> $decorators
     * @param Context $context
     * @return string
     */
    public function decorate(
        string  $content,
        array   $decorators,
        Context $context,
    ): string;
}
