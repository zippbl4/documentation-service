<?php

namespace App\AsyncTask;

use App\AsyncTask\Contracts\HandlerClassStringContract;
use App\AsyncTask\Contracts\Runners\PubSubTaskRunnerContract;
use App\AsyncTask\Contracts\Runners\QueueTaskRunnerContract;
use App\AsyncTask\Contracts\WorkerFactoryContract;
use App\AsyncTask\Factories\WorkerFactory;
use App\AsyncTask\Runners\PubSubTaskRunner;
use App\AsyncTask\Runners\QueueTaskRunner;
use App\AsyncTask\Services\HandlerClassString;
use Illuminate\Support\ServiceProvider;

class AsyncTaskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WorkerFactoryContract::class, WorkerFactory::class);
        $this->app->bind(HandlerClassStringContract::class, HandlerClassString::class);

        $this->registerRunners();
    }

    public function registerRunners(): void
    {
        $this->app->bind(QueueTaskRunnerContract::class, QueueTaskRunner::class);
        $this->app->bind(PubSubTaskRunnerContract::class, PubSubTaskRunner::class);
    }
}
