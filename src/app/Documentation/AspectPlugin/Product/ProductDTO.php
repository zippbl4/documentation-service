<?php

namespace App\Documentation\AspectPlugin\Product;

final readonly class ProductDTO
{
    public function __construct(
        public int $aspect_id,

        public string $lang,
        public string $product,
        public string $version,

        public string $archiveHash,
        public string $jobUuid,

        public string $rootFolder,
        public string $rootPath,
    ) {
    }
}
