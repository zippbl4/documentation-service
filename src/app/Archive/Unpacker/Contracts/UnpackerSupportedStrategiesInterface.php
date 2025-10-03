<?php

namespace App\Archive\Unpacker\Contracts;

interface UnpackerSupportedStrategiesInterface
{
    /**
     * @return list<string>
     */
    public function getSupportedStrategies(): array;
}
