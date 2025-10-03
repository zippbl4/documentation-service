<?php

namespace App\AsyncTask\Factories;

use App\AsyncTask\Contracts\WorkerFactoryContract;

class WorkerFactory implements WorkerFactoryContract
{
    private array $handlers;

    public function add(string $name, string $class): void
    {
        $this->handlers[$name][] = $class;
    }

    public function get(string $name): array
    {
        return $this->handlers[$name];
    }
}
