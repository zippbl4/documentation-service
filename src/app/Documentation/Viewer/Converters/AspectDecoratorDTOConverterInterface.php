<?php

namespace App\Documentation\Viewer\Converters;

use App\Documentation\Aspect\DTO\AspectDecoratorDTO;


/**
 * @deprecated
 */
interface AspectDecoratorDTOConverterInterface
{
    public function convertToInboundDecoratorDTO(AspectDecoratorDTO $aspectDecoratorDTO): array;
}
