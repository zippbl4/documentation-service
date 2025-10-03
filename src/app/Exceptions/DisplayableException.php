<?php

namespace App\Exceptions;

class DisplayableException extends \Exception
{
    private readonly string $clientMessage;

    public function __construct(string $clientMessage, string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $this->clientMessage = $clientMessage;

        parent::__construct($message, $code, $previous);
    }

    public function getClientMessage(): string
    {
        return $this->clientMessage;
    }
}
