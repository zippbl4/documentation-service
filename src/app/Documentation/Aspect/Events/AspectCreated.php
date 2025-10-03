<?php

namespace App\Documentation\Aspect\Events;

use App\Documentation\Aspect\Entities\Aspect;

readonly class AspectCreated
{
    public function __construct(public Aspect $aspect)
    {
    }
}
