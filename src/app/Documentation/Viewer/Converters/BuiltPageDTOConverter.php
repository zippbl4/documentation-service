<?php

namespace App\Documentation\Viewer\Converters;

use App\Documentation\Aspect\DTO\BuiltPathDTO;
use App\Page\Driver\DTO\DriverRequestDTO;

/**
 * @deprecated
 */
class BuiltPageDTOConverter implements BuiltPageDTOConverterInterface
{
    public function convertToDriverRequestDTO(BuiltPathDTO $builtPath): DriverRequestDTO
    {
        return new DriverRequestDTO(
            filters: $builtPath->getFilters(),
            rootWithPath: $builtPath->getRootWithPath(),
        );
    }
}
