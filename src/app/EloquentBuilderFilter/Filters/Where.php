<?php

namespace App\EloquentBuilderFilter\Filters;

use App\EloquentBuilderFilter\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class Where extends Filter
{
    protected string $operator = '=';

    protected string $boolean = 'and';

    public function __construct(
        private readonly string $column,
        private readonly mixed $value
    ) {
    }

    public function handle(Builder $builder, \Closure $next): mixed
    {
        $builder->when(isset($this->value), function (Builder $builder): Builder {
            return $builder->where(
                $this->column,
                $this->operator,
                $this->value,
                $this->boolean
            );
        });

        return $next($builder);
    }
}
