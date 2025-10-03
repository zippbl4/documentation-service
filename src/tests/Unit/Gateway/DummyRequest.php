<?php

namespace Tests\Unit\Gateway;

use App\Gateway\Contracts\RequestInterface;

class DummyRequest implements RequestInterface
{

    public function getMethod(): string
    {
        return '';
    }

    public function getUrl(): string
    {
        return '';
    }

    public function __toString()
    {
        return '';
    }
}
