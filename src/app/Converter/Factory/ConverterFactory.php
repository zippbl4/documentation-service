<?php

namespace App\Converter\Factory;

use App\Converter\Contracts\ConverterContract;

class ConverterFactory
{
    /**
     * @var ConverterContract[]
     */
    private array $converters = [];

    public function addConverter(string $from, string $to, ConverterContract $converter): void
    {
        $this->converters[$this->getKey($from, $to)] = $converter;
    }

    public function getConverter(object $from, string $to): ConverterContract
    {
        return $this->converters[$this->getKey($from::class, $to)];
    }

    private function getKey(string $from, string $to): string
    {
        return sprintf('%s:%s', $from, $to);
    }
}
