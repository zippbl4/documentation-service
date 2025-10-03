<?php

namespace App\Documentation\Aspect\Events;


use App\Documentation\Aspect\Entities\AspectConfig;

readonly class AspectConfigSaved
{
    public function __construct(public AspectConfig $config)
    {
    }
}
