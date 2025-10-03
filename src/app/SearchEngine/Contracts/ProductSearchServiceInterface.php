<?php

namespace App\SearchEngine\Contracts;

use App\SearchEngine\DTO\IndexDTO;
use App\SearchEngine\DTO\SearchDTO;
use App\SearchEngine\DTO\SearchResultDTO;

interface ProductSearchServiceInterface
{
    public function index(IndexDTO $dto): void;
    public function search(SearchDTO $dto): SearchResultDTO;
}
