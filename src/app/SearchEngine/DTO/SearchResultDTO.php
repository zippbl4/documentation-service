<?php

namespace App\SearchEngine\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\SerializedPath;

final readonly class SearchResultDTO
{
    public function __construct(
        #[SerializedPath('[hits][total][value]')]
        public int $count,
        /** @var HitDTO[] */
        #[SerializedPath('[hits][hits]')]
        public array $hits,
        #[SerializedName('query')]
        public string $query,
    ) {
    }
}
