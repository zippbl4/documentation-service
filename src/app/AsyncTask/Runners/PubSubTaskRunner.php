<?php

namespace App\AsyncTask\Runners;

use App\AsyncTask\Channels\RedisPubSubChannel;
use App\AsyncTask\Contracts\Runners\PubSubTaskRunnerContract;
use App\AsyncTask\Contracts\Runners\QueueTaskRunnerContract;
use App\AsyncTask\Services\HandlerClassString;
use App\AsyncTask\Services\TaskRunnerService;

/**
 * Пример:
 *
 * Создать 5 процессов:
 * - $writers - процесс publisher. В данном примере считывает файл построчно и публикует строки файла.
 * - $readers - процессы subscribers получающие строки файла и делающие с ними какие-то действия. Те все $readers получают все строки.
 *
 * Многопоточная обработка файла.
 *
 * <pre>
 * // senders to channel
 * $writers = [
 *      // Example:
 *      ReadFileLineByLine::class . ":{"file":"/path/to/file"}",
 * ];
 *
 * // receivers from channel
 * $readers = [
 *      // Example:
 *      HandeLine1::class,
 *      HandeLine2::class,
 *      HandeLine3::class,
 *      HandeLine4::class,
 * ];
 *
 * $runner = $this->app->make(PubSubTaskRunner::class);
 * $runner->run($writers, $readers);
 * </pre>
 *
 *  @see HandlerClassString
 */
final class PubSubTaskRunner implements PubSubTaskRunnerContract
{
    public function __construct(
        private TaskRunnerService $runner,
    ) {
    }

    public function run(array|string $writers, array|string $readers): void
    {
        $this->runner->run(
            RedisPubSubChannel::class,
            $writers,
            $readers
        );
    }
}
