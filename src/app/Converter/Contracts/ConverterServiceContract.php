<?php

namespace App\Converter\Contracts;

interface ConverterServiceContract
{
    /**
     * @template V
     * @param object $from
     * @param class-string<V> $to
     * @return V
     */
    public function convert(object $from, string $to): object;
}
