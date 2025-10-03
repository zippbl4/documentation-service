<?php

namespace App\Documentation\Viewer\Converters;

use App\Converter\Attributes\ExpectedType;
use App\Converter\Contracts\ConverterContract;
use App\Documentation\Aspect\DTO\BuiltPathDTO;
use App\Page\Driver\DTO\DriverRequestDTO;

class BuiltPageDTO2DriverRequestDTO implements ConverterContract
{
    /**
     * @param object&BuiltPathDTO $from
     * @return DriverRequestDTO
     */
    public function convert(#[ExpectedType(BuiltPathDTO::class)] object $from): DriverRequestDTO
    {
        return new DriverRequestDTO(
            filters: $from->getFilters(),
            rootWithPath: $from->getRootWithPath(),
        );
    }
}
