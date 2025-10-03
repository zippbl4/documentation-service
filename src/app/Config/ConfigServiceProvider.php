<?php

namespace App\Config;

use App\Config\DTO\ConfigDTO;
use App\Config\Repositories\SettingsEloquentStorage;
use App\Config\Repositories\SettingsInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SettingsInterface::class, SettingsEloquentStorage::class);
    }

    public function boot(): void
    {
        $this->app->extend('config', function (Repository $config) {
            return $this->app->make(ConfigAdapter::class)->fillConfig();
        });

        $this->app->scoped(ConfigDTO::class, function () {
            return $this->app->make(ConfigAdapter::class)->makeConfigDTO();
        });
    }
}
