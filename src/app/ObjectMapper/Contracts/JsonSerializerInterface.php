<?php

namespace App\ObjectMapper\Contracts;

interface JsonSerializerInterface
{
    /**
     * @param mixed $data
     * @return string
     */
    public function serialize(mixed $data): string;
}
