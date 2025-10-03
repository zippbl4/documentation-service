<?php

namespace App\Documentation\AspectPlugin\PageDriver\Handlers;

use App\ContentEngine\Contracts\ReadabilityInterface;
use App\Documentation\AspectPlugin\PageDriver\Repositories\MysqlPageRepository;
use App\Documentation\Researcher\Contracts\Handler;
use App\Documentation\Researcher\DTO\FileDTO;
use Psr\Log\LoggerInterface;

/**
 * @deprecated
 */
final class DatabaseSaverHandler implements Handler
{
    private array $chunk = [];

    private int $chunkSize = 1000;

    public function __construct(
        private LoggerInterface      $logger,
        private MysqlPageRepository  $pageRepository,
        private ReadabilityInterface $readability,
    ) {
    }

    public function handle(FileDTO $file): void
    {
        try {
            $readability = $this->readability->parse($file->content);
            $this->chunk[] = array_merge($file->toArray(), [
                'title' => $readability?->title ?? $file->page,
                'content' => $readability?->content ?? $file->content,
            ]);
        } catch (\Throwable $e) {
            $this->logger->error("DatabaseSaverHandler: error: " . $e->getMessage(), [
                'file' => $file->toArray(),
            ]);

            return;
        }

        if (count($this->chunk) > $this->chunkSize) {
            $this->flush();
        }
    }

    public function flush(): void
    {
        $this->pageRepository->updateOrCreateMany($this->chunk);
        $this->chunk = [];
    }
}
