<?php

namespace App\Documentation\Searcher\Services;

use App\Documentation\Viewer\DTO\PageResponseDTO;
use App\Documentation\Searcher\DTO\SearchRequestDTO;
use App\SearchEngine\Contracts\ProductSearchServiceInterface;
use App\SearchEngine\DTO\SearchDTO;
use App\TemplateEngine\Contracts\TemplatesEngineContract;

final readonly class PageSearcherService
{
    public function __construct(
        private ProductSearchServiceInterface $searchService,
        private TemplatesEngineContract       $templatesEngine,
    ) {
    }

    public function searchPage(SearchRequestDTO $request): PageResponseDTO
    {
        $result = $this
            ->searchService
            ->search(new SearchDTO(
                query: $request->query,
            ));

        return new PageResponseDTO(
            status: 200,
            content: $this->templatesEngine->renderView('pages.search', compact('result')),
            contentType: 'text/html',
        );
    }
}
