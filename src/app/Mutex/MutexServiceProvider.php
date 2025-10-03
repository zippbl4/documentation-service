<?php

namespace App\Mutex;

use App\Mutex\Contracts\MutexInterface;
use App\Mutex\Services\MutexCacheStorage;
use Illuminate\Support\ServiceProvider;

class MutexServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MutexInterface::class, MutexCacheStorage::class);
    }
}
