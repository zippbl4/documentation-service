<?php

namespace App\ContentEngine;

use App\ContentEngine\Contracts\DiffInterface;
use App\ContentEngine\Contracts\ReadabilityInterface;
use App\ContentEngine\Diff\DiffSebastianWrapper;
use App\ContentEngine\Readability\ReadabilityJSWrapper;
use Illuminate\Support\ServiceProvider;

class ContentEngineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ReadabilityInterface::class, ReadabilityJSWrapper::class);
        $this->app->bind(DiffInterface::class, DiffSebastianWrapper::class);
    }
}
