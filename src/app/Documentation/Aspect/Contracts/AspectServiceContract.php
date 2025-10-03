<?php

namespace App\Documentation\Aspect\Contracts;

use App\Documentation\Aspect\DTO\AspectDTO;
use App\Documentation\Aspect\DTO\AspectIdDTO;

interface AspectServiceContract
{
    public function getAspect(int $aspectId): AspectDTO;
    public function getAspectByAspectId(AspectIdDTO $aspectId): AspectDTO;
}
