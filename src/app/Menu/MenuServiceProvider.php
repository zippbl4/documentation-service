<?php

namespace App\Menu;

use App\Config\DTO\ConfigDTO;
use App\Menu\Composers\MenuComposer;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerCommand();
    }

    public function boot(Dispatcher $events, ConfigDTO $config): void
    {
        $this->bootView($config);
        $this->bootEvents($events);
    }

    public function bootView(ConfigDTO $config): void
    {
        Facades\View::composer($config->template . '.layout', MenuComposer::class);
    }

    public function bootEvents(Dispatcher $events): void
    {
    }

    public function registerCommand(): void
    {
        $this->commands([]);
    }
}
