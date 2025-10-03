<?php

namespace App\Notes;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class NotesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerInertia();
    }

    public function boot(): void
    {
        $this->bootRoutes();
    }

    public function registerInertia(): void
    {
        Inertia::setRootView('no-name.notes.inertia');
    }

    public function bootRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/notes.php');
    }
}
