<?php

namespace App\Gateway\Contracts;

interface RequestInterface extends \Stringable
{
    public function getMethod(): string;
    public function getUrl(): string;
}
