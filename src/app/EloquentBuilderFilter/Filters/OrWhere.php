<?php

namespace App\EloquentBuilderFilter\Filters;

class OrWhere extends Where
{
    protected string $boolean = 'or';
}
