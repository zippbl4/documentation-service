<?php

namespace App\Documentation\Access\Contracts;

use Illuminate\Http\Request;

interface ContentAccessManagerInterface
{
    public function can(Request $request): bool;
}
