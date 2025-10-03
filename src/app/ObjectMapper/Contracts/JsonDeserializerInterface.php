<?php

namespace App\ObjectMapper\Contracts;

interface JsonDeserializerInterface
{
    /**
     * @template T
     * @param mixed $data
     * @param class-string<T> $class
     * @return T
     */
    public function deserialize(mixed $data, string $class): object;

    /**
     * @param mixed $data
     * @return string
     */
    public function serialize(mixed $data): string;
}
