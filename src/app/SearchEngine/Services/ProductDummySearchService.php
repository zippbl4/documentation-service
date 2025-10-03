<?php

namespace App\SearchEngine\Services;

use App\Contracts\NameInterface;
use App\SearchEngine\Contracts\ProductSearchServiceInterface;
use App\SearchEngine\DTO\HitDTO;
use App\SearchEngine\DTO\IndexDTO;
use App\SearchEngine\DTO\SearchDTO;
use App\SearchEngine\DTO\SearchResultDTO;
use Illuminate\Events\Dispatcher;

final readonly class ProductDummySearchService implements ProductSearchServiceInterface, NameInterface
{
    public function __construct(
        private Dispatcher $dispatcher,
    ) {
    }

    public static function getName(): string
    {
        return 'dummy';
    }

    public function index(IndexDTO $dto): void
    {

    }

    public function search(SearchDTO $dto): SearchResultDTO
    {
        return new SearchResultDTO(
            count: 1,
            hits: [
                new HitDTO(
                    index: 'ru',
                    type: 'r1018b',
                    id: 1,
                    title: 'example',
                    body: 'example',
                ),
            ],
        );
    }
}
