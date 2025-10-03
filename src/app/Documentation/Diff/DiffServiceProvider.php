<?php

namespace App\Documentation\Diff;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class DiffServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootRoutes();
    }

    public function bootRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
    }

    public function register(): void
    {
        //$this->registerInertia();
    }

    public function registerInertia(): void
    {
        Inertia::setRootView('confluence.inertia');
    }
}
