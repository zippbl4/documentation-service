<?php

namespace App\AsyncTask\Contracts;

interface WorkerFactoryContract
{
    public function add(string $name, string $class): void;
    public function get(string $name): array;
}
