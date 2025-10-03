<?php

namespace App\Documentation\Uploader\Events;

class ProductUploaded
{
    public function __construct(
        public int $aspectId,
        public string $jobUuid,
        public string $productPath,
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
