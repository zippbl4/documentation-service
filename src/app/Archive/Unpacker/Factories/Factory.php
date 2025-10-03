<?php

namespace App\Archive\Unpacker\Factories;

use App\Archive\Unpacker\Contracts\UnpackerFactoryInterface;
use App\Archive\Unpacker\Contracts\UnpackerStrategy;
use App\Archive\Unpacker\Contracts\UnpackerSupportedStrategiesInterface;

final class Factory implements UnpackerFactoryInterface, UnpackerSupportedStrategiesInterface
{
    private array $unpackers = [];

    public function add(string $name, UnpackerStrategy $unpacker): void
    {
        $this->unpackers[$name] = $unpacker;
    }

    public function get(string $name): UnpackerStrategy
    {
        if (!isset($this->unpackers[$name])) {
            throw new \InvalidArgumentException(
                "No unpacker '{$name}'"
            );
        }

        return $this->unpackers[$name];
    }

    public function getSupportedStrategies(): array
    {
        return array_keys($this->unpackers);
    }
}
