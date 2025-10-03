<?php

namespace App\Documentation\Viewer\Converters;


use App\Documentation\Aspect\DTO\AspectDecoratorDTO;
use App\Documentation\Aspect\DTO\AspectDecoratorItemDTO;
use App\Page\Decorator\DTO\InboundDecoratorDTO;

/**
 * @deprecated
 */
class AspectDecoratorDTOConverter implements AspectDecoratorDTOConverterInterface
{
    public function convertToInboundDecoratorDTO(AspectDecoratorDTO $aspectDecoratorDTO): array
    {
        return array_map(
            $this->convertOneItemToInboundDecoratorDTO(),
            $aspectDecoratorDTO->all(),
        );
    }

    private function convertOneItemToInboundDecoratorDTO(): callable
    {
        return fn (AspectDecoratorItemDTO $item) => new InboundDecoratorDTO(
            name: $item->getDecoratorName(),
            userCustomTemplate: $item->getUserCustomTemplate(),
        );
    }
}
