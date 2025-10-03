<?php

namespace App\Archive\Unpacker\Contracts;

use App\Contracts\NameInterface;
use Psr\Log\LoggerInterface;

abstract readonly class BaseUnpackerStrategy implements UnpackerStrategy, NameInterface
{
    protected LoggerInterface $logger;

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
