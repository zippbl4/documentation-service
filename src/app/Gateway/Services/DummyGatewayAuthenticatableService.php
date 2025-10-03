<?php

namespace App\Gateway\Services;

use App\Gateway\Contracts\GatewayAuthenticatableInterface;
use App\Gateway\DTO\Auth\LoginResponseObject;
use App\ObjectMapper\Contracts\JsonDeserializerInterface;

final readonly class DummyGatewayAuthenticatableService implements GatewayAuthenticatableInterface
{
    public function __construct(private JsonDeserializerInterface $deserializer) {
    }

    public function login(string $email, string $password): LoginResponseObject
    {
        return $this->deserializer->deserialize(
            file_get_contents(__DIR__ . '/dummy.json'),
            LoginResponseObject::class
        );
    }
}
