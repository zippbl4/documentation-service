<?php

namespace App\Documentation\Searcher\Listeners;

use App\Documentation\Researcher\Contracts\Handler;
use App\Documentation\Researcher\DTO\FileDTO;
use App\SearchEngine\Contracts\ProductSearchServiceInterface;
use App\SearchEngine\DTO\IndexDTO;
use Psr\Log\LoggerInterface;

/**
 * @deprecated
 */
final readonly class IndexerHandler implements Handler
{
    public function __construct(
        private LoggerInterface               $logger,
        private ProductSearchServiceInterface $searchService,
    ) {
    }

    public function handle(FileDTO $file): void
    {
        $this->searchService->index(new IndexDTO(
            lang: $file->lang,
            product: $file->product,
            version: $file->version,
            page: $file->page,
            content: $file->content,
        ));
    }
}
