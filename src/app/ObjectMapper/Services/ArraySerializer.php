<?php

namespace App\ObjectMapper\Services;

use App\ObjectMapper\Contracts\ArrayDeserializerInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

final readonly class ArraySerializer implements ArrayDeserializerInterface
{
    public function __construct(private SymfonySerializer $serializer)
    {
    }

    public function deserialize(mixed $data, string $class): object
    {
        return $this->serializer->denormalize(
            $data,
            $class,
        );
    }
}
