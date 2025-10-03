<?php

namespace App\Mutex\Services;

use App\Mutex\Contracts\MutexInterface;
use Illuminate\Support\Facades\Cache;

class MutexCacheStorage implements MutexInterface
{
    public function __construct()
    {
    }

    /**
     * @param string $name
     * @param int $seconds
     *
     * @return bool
     */
    public function lock(string $name = 'lock', int $seconds = MutexInterface::DEFAULT_SECONDS): bool
    {
        if ($seconds < 0) {
            $seconds = MutexInterface::DEFAULT_SECONDS;
        }

        $lock = Cache::lock($name, $seconds);

        return $lock->get();
    }

    /**
     * @param string $name
     */
    public function unlock(string $name = 'lock'): void
    {
        Cache::lock($name)->forceRelease();
    }

    /**
     * @param string $name
     * @param string|int|null $unique
     *
     * @return string
     */
    public function getUniqueKey(string $name, $unique): string
    {
        $name = \str_replace(' ', '_', $name);

        return MutexInterface::PREFIX_FOR_LOCKS . '_' . $name . '_' . $unique;
    }
}
