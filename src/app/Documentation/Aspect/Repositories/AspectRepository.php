<?php

namespace App\Documentation\Aspect\Repositories;

use App\Documentation\Aspect\Contracts\AspectRepositoryContract;
use App\Documentation\Aspect\DTO\AspectIdDTO;
use App\Documentation\Aspect\Entities\Aspect;
use App\Documentation\Aspect\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AspectRepository implements AspectRepositoryContract
{
    public function findAll(): Collection
    {
        return $this
            ->getBuilder()
            ->orderBy('sort_order')
            ->get();
    }

    public function findById(int $id): ?Aspect
    {
        $builder = $this
            ->getBuilder()
            ->where('id', $id)
        ;

        return $this
            ->getBase($builder)
            ->first();
    }

    public function findByIdWithoutDependencies(int $id): ?Aspect
    {
        return $this->getBuilder()->find($id);
    }

    public function findByAspectIdRegex(AspectIdDTO $id): ?Aspect
    {
        $builder = $this
            ->getBuilder()
            // MYSQL
            ->whereRaw("regexp_instr(?, lang)", [$id->lang])
            ->whereRaw("regexp_instr(?, product)", [$id->product])
            ->whereRaw("regexp_instr(?, version)", [$id->version])
        ;

        return $this
            ->getBase($builder)
            ->first();
    }

    public function findByAspectId(AspectIdDTO $id): ?Aspect
    {
        $builder = $this
            ->getBuilder()
            ->where("lang", $id->lang)
            ->where("product", $id->product)
            ->where("version", $id->version)
        ;

        return $this
            ->getBase($builder)
            ->first();
    }

    public function getDecoratorsAspects(): Collection
    {
        return $this
            ->getBuilder()
            ->where('status', StatusEnum::Enabled)
            ->whereHas('decorators', function (Builder $builder) {
                $builder->where('aspect_decorator.status', StatusEnum::Enabled);
            })
            ->with('decorators', function (BelongsToMany $builder) {
                $builder->orderBy('aspect_decorator.sort_order');
            })
            ->orderBy('sort_order')
            ->get();
    }

    private function getBase(Builder $builder): Builder
    {
        return $builder
            ->where('status', StatusEnum::Enabled)
            ->whereHas('path')
            ->with([
                'path',
                'mappers' => function (HasMany $builder) {
                    $builder
                        ->where('status', StatusEnum::Enabled);
                },
                'settings' => function (HasMany $builder) {
                    $builder
                        ->where('status', StatusEnum::Enabled);
                },
                'decorators' => function (HasMany $builder) {
                    $builder
                        ->where('status', StatusEnum::Enabled)
                        ->orderBy('sort_order');
                },
            ])
            ->orderBy('sort_order')
        ;
    }

    private function getBuilder(): Builder
    {
        return Aspect::query();
    }
}
