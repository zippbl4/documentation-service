<?php

namespace App\Page\Driver\Contracts;

interface SupportedDriversInterface
{
    public function getSupportedDrivers(): array;
}
