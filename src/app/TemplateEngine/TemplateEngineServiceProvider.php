<?php

namespace App\TemplateEngine;

use App\TemplateEngine\Contracts\TemplatesEngineContract;
use App\TemplateEngine\Services\TemplatesEngineService;
use Illuminate\Support\ServiceProvider;

class TemplateEngineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TemplatesEngineContract::class, TemplatesEngineService::class);
    }
}
