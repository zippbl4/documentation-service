<?php

namespace App\Documentation\Aspect\Services;

use App\Config\DTO\ConfigDTO;
use App\Documentation\Aspect\Contracts\AspectConverterInterface;
use App\Documentation\Aspect\Contracts\AspectRepositoryContract;
use App\Documentation\Aspect\Contracts\AspectServiceContract;
use App\Documentation\Aspect\DTO\AspectDTO;
use App\Documentation\Aspect\DTO\AspectIdDTO;
use App\Documentation\Aspect\Exceptions\AspectNotFoundException;

class AspectService implements AspectServiceContract
{
    public function __construct(
        private ConfigDTO                $config,
        private AspectRepositoryContract $repository,
        private AspectConverterInterface $converter,
    ) {
    }

    public function getAspect(int $aspectId): AspectDTO
    {
        $entity = $this->repository->findById($aspectId);

        if ($entity === null) {
            throw new AspectNotFoundException('No aspect found: ' . $aspectId);
        }

        return $this->converter->convertToAspect($entity);
    }

    public function getAspectByAspectId(AspectIdDTO $aspectId): AspectDTO
    {
        if (in_array($aspectId, $this->config->listOfAspectsWorkDirectly)) {
            $entity = $this->repository->findByAspectId($aspectId);
        } else {
            $entity = $this->repository->findByAspectIdRegex($aspectId);
        }

        if ($entity === null) {
            throw new AspectNotFoundException('No aspect found: ' . $aspectId);
        }

        return $this->converter->convertToAspect($entity);
    }
}
