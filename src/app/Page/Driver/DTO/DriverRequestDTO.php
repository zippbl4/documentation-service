<?php

namespace App\Page\Driver\DTO;

final readonly class DriverRequestDTO
{
    public function __construct(
        private array $filters,
        private string $rootWithPath,
    ) {
    }

    /**
     * @example /var/www/storage/app/release//R2018b/R2018b_rus/matlab/index.html
     * @return string
     */
    public function getRootWithPath(): string
    {
        return $this->rootWithPath;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }
}
