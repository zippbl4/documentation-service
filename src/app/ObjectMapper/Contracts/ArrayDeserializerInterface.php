<?php

namespace App\ObjectMapper\Contracts;

interface ArrayDeserializerInterface
{
    /**
     * @template T
     * @param mixed $data
     * @param class-string<T> $class
     * @return T
     */
    public function deserialize(mixed $data, string $class): object;
}
