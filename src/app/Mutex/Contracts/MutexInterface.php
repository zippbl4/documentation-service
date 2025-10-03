<?php

namespace App\Mutex\Contracts;

interface MutexInterface
{
    public const PREFIX_FOR_LOCKS = 'LOCK';
    public const DEFAULT_SECONDS = 60;

    public function lock(string $name = 'lock', int $seconds = self::DEFAULT_SECONDS): bool;

    public function unlock(string $name = 'lock'): void;
}
