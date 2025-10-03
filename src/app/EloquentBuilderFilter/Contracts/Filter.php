<?php

namespace App\EloquentBuilderFilter\Contracts;

use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    public abstract function handle(Builder $builder, \Closure $next): mixed;
}
