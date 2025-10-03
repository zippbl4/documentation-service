<?php

namespace App\SearchEngine\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\SerializedPath;

final readonly class HitDTO
{
    public function __construct(
        #[SerializedName('_index')]
        public string $index,
        #[SerializedName('_type')]
        public string $type,
        #[SerializedName('_id')]
        public string $id,
        #[SerializedPath('[_source][title]')]
        public string $title,
        #[SerializedPath('[_source][body]')]
        public string $body,
//        #[SerializedName('_source')]
//        public BodyDTO $body,
        #[SerializedPath('[_source][product][lang]')]
        public string $productLang,
        #[SerializedPath('[_source][product][product]')]
        public string $product,
        #[SerializedPath('[_source][product][version]')]
        public string $productVersion,
    ) {
    }
}
