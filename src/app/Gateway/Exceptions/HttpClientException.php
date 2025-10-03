<?php

namespace App\Gateway\Exceptions;

class HttpClientException extends \Exception
{
    private readonly string $content;

    public function __construct(string $message, string $content = '', ?\Throwable $previous = null)
    {
        $this->content = $content;

        parent::__construct($message, 0, $previous);
    }
}
