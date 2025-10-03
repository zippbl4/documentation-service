<?php

namespace App\Documentation\Aspect\Contracts;

use App\Documentation\Aspect\DTO\AspectIdDTO;
use App\Documentation\Aspect\Entities\Aspect;
use Illuminate\Database\Eloquent\Collection;

interface AspectRepositoryContract
{
    public function findAll(): Collection;

    public function findById(int $id): ?Aspect;

    public function findByAspectIdRegex(AspectIdDTO $id): ?Aspect;

    public function findByAspectId(AspectIdDTO $id): ?Aspect;

}
