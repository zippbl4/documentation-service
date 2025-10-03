<?php

namespace App\Documentation\Viewer\Converters;

use App\Documentation\Aspect\DTO\BuiltPathDTO;
use App\Page\Driver\DTO\DriverRequestDTO;

/**
 * @deprecated
 */
interface BuiltPageDTOConverterInterface
{
    public function convertToDriverRequestDTO(BuiltPathDTO $builtPath): DriverRequestDTO;
}
