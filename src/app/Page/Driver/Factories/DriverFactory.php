<?php

namespace App\Page\Driver\Factories;

use App\Page\Driver\Contracts\Driver;
use App\Page\Driver\Contracts\DriverFactoryInterface;
use App\Page\Driver\Contracts\SupportedDriversInterface;

final class DriverFactory implements DriverFactoryInterface, SupportedDriversInterface
{
    /**
     * @var array<string, Driver>
     */
    private array $drivers = [];

    public function addDriver(Driver $driver): void
    {
        $this->drivers[$driver::getName()] = $driver;
    }

    public function getDriver(string $name): Driver
    {
        if (!isset($this->drivers[$name])) {
            throw new \InvalidArgumentException(
                "No driver '{$name}'"
            );
        }

        return $this->drivers[$name];
    }

    public function getSupportedDrivers(): array
    {
        return array_keys($this->drivers);
    }
}
