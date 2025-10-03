<?php

namespace App\Documentation\Viewer\Converters;


use App\Converter\Attributes\ExpectedType;
use App\Converter\Contracts\ConverterContract;
use App\Documentation\Aspect\DTO\AspectDecoratorDTO;
use App\Documentation\Aspect\DTO\AspectDecoratorItemDTO;
use App\Page\Decorator\DTO\InboundDecoratorDTO;
use ArrayObject;

class AspectDecoratorDTO2ArrayObject implements ConverterContract
{
    /**
     * @see https://www.php.net/manual/ru/class.arrayobject.php
     * @param object&AspectDecoratorDTO $from
     * @return ArrayObject
     */
    public function convert(#[ExpectedType(AspectDecoratorDTO::class)] object $from): ArrayObject
    {
        return new ArrayObject(array_map(
            $this->convertOneItemToInboundDecoratorDTO(),
            $from->all(),
        ));
    }

    private function convertOneItemToInboundDecoratorDTO(): callable
    {
        return fn (AspectDecoratorItemDTO $item) => new InboundDecoratorDTO(
            name: $item->getDecoratorName(),
            userCustomTemplate: $item->getUserCustomTemplate(),
        );
    }
}
