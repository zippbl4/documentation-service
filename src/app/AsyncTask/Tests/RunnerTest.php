<?php

namespace App\AsyncTask\Tests;

use App\AsyncTask\Contracts\Runners\QueueTaskRunnerContract;
use Tests\TestCase;

class RunnerTest extends TestCase
{
    public function testRun()
    {
        $runner = $this->app->make(QueueTaskRunnerContract::class);
        $runner->run(

        );
    }
}
