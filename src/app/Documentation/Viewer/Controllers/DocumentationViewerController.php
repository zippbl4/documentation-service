<?php

namespace App\Documentation\Viewer\Controllers;

use App\Documentation\Viewer\Contracts\RequestConverterContract;
use App\Documentation\Viewer\DTO\PageResponseDTO;
use App\Documentation\Viewer\Services\PageViewerInterface;
use Illuminate\Http\Request;

final readonly class DocumentationViewerController
{
    public function __construct(
        private PageViewerInterface      $pageManager,
        private RequestConverterContract $requestConverter,
    ) {
    }

    public function show(Request $request): PageResponseDTO
    {
        return $this
            ->pageManager
            ->showPage(
                $this->requestConverter->convertToPageRequestDTO($request)
            );
    }
}
