<?php

namespace App\EloquentBuilderFilter\Filters;

use App\EloquentBuilderFilter\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class Between extends Filter
{
    public function __construct(
        private readonly string $column,
        private readonly array $values,
    ) {
    }

    public function handle(Builder $builder, \Closure $next): mixed
    {
        $builder->when(isset($this->values), function (Builder $builder): Builder {
            return $builder->whereBetween($this->column, $this->values);
        });

        return $next($builder);
    }
}
