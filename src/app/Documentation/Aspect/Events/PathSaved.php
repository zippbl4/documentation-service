<?php

namespace App\Documentation\Aspect\Events;

use App\Documentation\Aspect\Entities\Path;

readonly class PathSaved
{
    public function __construct(public Path $path)
    {
    }
}
