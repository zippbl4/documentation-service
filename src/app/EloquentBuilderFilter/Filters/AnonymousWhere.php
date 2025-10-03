<?php

namespace App\EloquentBuilderFilter\Filters;

use App\EloquentBuilderFilter\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class AnonymousWhere extends Filter
{
    public function __construct(
        /** @var Filter[] */
        private readonly array $wheres
    ) {
    }

    public function handle(Builder $builder, \Closure $next): mixed
    {
        $builder->when(isset($this->wheres), function (Builder $builder) use ($next): Builder {
            return $builder->where(function (Builder $builder) use ($next): Builder {
                foreach ($this->wheres as $where) {
                    $where->handle($builder, $next);
                }
                return $builder;
            });
        });

        return $next($builder);
    }
}
