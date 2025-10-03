<?php

namespace App\Documentation\Correction\DTO;

final readonly class CorrectionDTO
{
    public function __construct(
        public string $releaseName,
        public string $pageUrl,
        public string $pageXpath,
        public string $contentEng,
        public string $contentRusOld,
        public string $contentRusNew,
    ) {
    }
}
