<?php

namespace App\Documentation\Access\Contracts;

use Illuminate\Http\Request;

interface BaseRule
{
    public function handle(Request $request, \Closure $next): mixed;
}
