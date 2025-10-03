<?php

namespace App\AsyncTask\Runners;

use App\AsyncTask\Channels\RedisQueueChannel;
use App\AsyncTask\Contracts\Runners\QueueTaskRunnerContract;
use App\AsyncTask\Services\HandlerClassString;
use App\AsyncTask\Services\TaskRunnerService;

/**
 * Пример:
 *
 * Создать 5 процессов:
 * - $writers - процесс записывающий в очередь. В данном примере считывает файл построчно и отправляет в очередь строки файла.
 * - $readers - процессы берущие строки из очереди и делающие с ними какие-то действия.
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
 *      HandeLine::class,
 *      HandeLine::class,
 *      HandeLine::class,
 *      HandeLine::class,
 * ];
 *
 * $runner = $this->app->make(QueueTaskRunner::class);
 * $runner->run($writers, $readers);
 * </pre>
 *
 *  @see HandlerClassString
 */
final class QueueTaskRunner implements QueueTaskRunnerContract
{
    public function __construct(
        private TaskRunnerService $runner,
    ) {
    }

    public function run(array|string $writers, array|string $readers): void
    {
        $this->runner->run(
            RedisQueueChannel::class,
            $writers,
            $readers
        );
    }
}
