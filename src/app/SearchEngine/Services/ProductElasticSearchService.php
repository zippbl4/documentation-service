<?php

namespace App\SearchEngine\Services;

use App\Contracts\NameInterface;
use App\ObjectMapper\Contracts\ArrayDeserializerInterface;
use App\SearchEngine\Contracts\ProductSearchServiceInterface;
use App\SearchEngine\DTO\IndexDTO;
use App\SearchEngine\DTO\SearchDTO;
use App\SearchEngine\DTO\SearchResultDTO;
use Elasticsearch\Client;

final readonly class ProductElasticSearchService implements ProductSearchServiceInterface, NameInterface
{
    public function __construct(
        private Client                     $client,
        private ArrayDeserializerInterface $deserializer,
        private PreparerService            $preparerService,
    ) {
    }

    public static function getName(): string
    {
        return 'elastic';
    }

    public function index(IndexDTO $dto): void
    {
        $this->createIndexIfNotExists($dto->version);

        $this->client->index([
            //"index_" .$releaseName
            // version
            'index' => strtolower($dto->version),
            // "type_" .$releaseName
            // lang
            'type' => strtolower($dto->lang),
            // preg_match("/\w+_rus\/(.+)$/", $path, $rus)
            // $path2 = $rus[1];
            // page
            'id' => $dto->page,
            'body' => $this->preparerService->prepareIndex($dto->content) + [
                'product' => [
                    'lang' => $dto->lang,
                    'product' => $dto->product,
                    'version' => $dto->version,
                ],
            ],
        ]);
    }

    public function search(SearchDTO $dto): SearchResultDTO
    {
        $search = $this->client->search([
            'q' => $dto->query,
        ]);

        $search['query'] = $dto->query;

        return $this
            ->deserializer
            ->deserialize($search, SearchResultDTO::class);
    }

    private function createIndexIfNotExists(string $indexName): void
    {
        static $exists = false;

        $params = ['index' => strtolower($indexName)];
        $indices = $this->client->indices();

        if (! $exists) {
            $exists = $indices->exists($params);
        }

        if (! $exists) {
            $indices->create($params);
        }
    }

    // TODO
    private function prepareSearch(string $text): array
    {
        return [
            'match' => [
                'body' => $text,
            ]
        ];
    }
}
