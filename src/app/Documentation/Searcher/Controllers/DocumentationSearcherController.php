<?php

namespace App\Documentation\Searcher\Controllers;

use App\Documentation\Searcher\Converters\RequestConverter;
use App\Documentation\Searcher\Services\PageSearcherService;
use App\Documentation\Viewer\DTO\PageResponseDTO;
use Illuminate\Http\Request;

final readonly class DocumentationSearcherController
{
    public function __construct(
        private PageSearcherService $pageSearcher,
        private RequestConverter    $requestConverter,
    ) {
    }

    public function search(Request $request): PageResponseDTO
    {
        return $this
            ->pageSearcher
            ->searchPage(
                $this->requestConverter->convertToSearchRequestDTO($request)
            );
    }
}
