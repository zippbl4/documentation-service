<?php

namespace App\Gateway\Contracts;

use App\Gateway\DTO\Auth\LoginResponseObject;

interface GatewayAuthenticatableInterface
{
    public function login(string $email, string $password): LoginResponseObject;
}
