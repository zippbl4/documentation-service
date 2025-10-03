<?php

namespace App\Converter\Services;

use App\Converter\Contracts\ConverterServiceContract;
use App\Converter\Factory\ConverterFactory;

class ConverterService implements ConverterServiceContract
{
    public function __construct(private ConverterFactory $factory)
    {
    }

    public function convert(object $from, string $to): object
    {
        return $this->factory->getConverter($from, $to)->convert($from);
    }
}
