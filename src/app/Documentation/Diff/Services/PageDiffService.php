<?php

namespace App\Documentation\Diff\Services;

use App\ContentEngine\Contracts\DiffInterface;
use App\ContentEngine\Contracts\ReadabilityInterface;
use App\Documentation\Diff\DTO\DiffResponseDTO;
use App\Documentation\Viewer\DTO\PageRequestDTO;
use App\Documentation\Viewer\Services\PageViewerInterface;

/**
 * @deprecated
 */
final readonly class PageDiffService
{
    public function __construct(
        private PageViewerInterface  $pageViewer,
        private ReadabilityInterface $readability,
        private DiffInterface        $diff,
    ) {
    }

    public function diffPages(PageRequestDTO $from, PageRequestDTO $to): DiffResponseDTO
    {
        $from = $this->pageViewer->showPage($from)->getContent();
//        $from = $this->readability->parse($from);
//        $from = $from->content;

        $to = $this->pageViewer->showPage($to)->getContent();
//        $to = $this->readability->parse($to);
//        $to = $to->content;

        $diff = $this->diff->diff($from, $to);

        return new DiffResponseDTO(
            from: $from,
            to: $to,
            diff: $diff,
        );
    }
}
