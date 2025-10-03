<?php

namespace App\Documentation\AspectPlugin\PageDriver\Repositories;


interface PageRepositoryInterface
{
    public function findByFilter(array $filter);
    public function create(array $uniquenessCondition, array $additionalData);
}
