<?php

namespace App\Page\Driver\Contracts;

interface DriverFactoryInterface
{
    public function getDriver(string $name): Driver;
}
