<?php

namespace App\Documentation\Viewer\Services;

use App\Documentation\Aspect\Exceptions\AspectNotFoundException;
use App\Documentation\Viewer\DTO\PageRequestDTO;
use App\Documentation\Viewer\DTO\PageResponseDTO;
use App\Page\Driver\Exceptions\DriverException;

interface PageViewerInterface
{
    /**
     * @param PageRequestDTO $request
     * @return PageResponseDTO
     * @throws AspectNotFoundException
     * @throws DriverException
     */
    public function showPage(PageRequestDTO $request): PageResponseDTO;
}
