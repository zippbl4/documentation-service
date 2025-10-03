<?php

namespace App\Gateway\DTO\Auth;

use App\Gateway\DTO\Me;

class LoginResponseObject
{
    public function __construct(
        public readonly ?string $token,
        public readonly ?Me $me,

        public ?bool $result,
        public mixed $errors,
    ) {

    }
}
