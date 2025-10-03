<?php

namespace App\Documentation\Aspect\Contracts;

use App\Documentation\Aspect\DTO\AspectDecoratorDTO;
use App\Documentation\Aspect\DTO\AspectDTO;
use App\Documentation\Aspect\DTO\AspectIdDTO;
use App\Documentation\Aspect\DTO\AspectMapperDTO;
use App\Documentation\Aspect\DTO\AspectPathDTO;
use App\Documentation\Aspect\Entities\Aspect;

/**
 * @deprecated
 */
interface AspectConverterInterface
{
    public function convertToAspect(Aspect $aspect): AspectDTO;

    public function convertToAspectId(Aspect $aspect): AspectIdDTO;

    public function convertToAspectPath(Aspect $aspect): AspectPathDTO;

    public function convertToDecorator(Aspect $aspect): AspectDecoratorDTO;

    public function convertToValidations(Aspect $aspect): array;

    public function convertToMapper(Aspect $aspect): AspectMapperDTO;
}
