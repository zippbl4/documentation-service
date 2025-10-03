<?php

namespace App\ObjectMapper\Services;

use App\ObjectMapper\Contracts\JsonDeserializerInterface;
use App\ObjectMapper\Contracts\JsonSerializerInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

final readonly class JsonSerializer implements JsonDeserializerInterface, JsonSerializerInterface
{
    public function __construct(private SymfonySerializer $serializer)
    {
    }

    public function deserialize(mixed $data, string $class): object
    {
        return $this->serializer->deserialize(
            $data,
            $class,
            'json'
        );
    }

    public function serialize(mixed $data): string
    {
        return $this->serializer->serialize(
            $data,
            'json'
        );
    }
}
