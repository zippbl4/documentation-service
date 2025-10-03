<?php

namespace App\Archive\Unpacker\Contracts;

interface UnpackerFactoryInterface
{
    public function get(string $name): UnpackerStrategy;
}
