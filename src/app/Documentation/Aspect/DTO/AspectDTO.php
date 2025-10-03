<?php

namespace App\Documentation\Aspect\DTO;

use App\Documentation\Aspect\Builders\AspectPathBuilder;

final readonly class AspectDTO
{
    public function __construct(
        public int                $entityId,
        public AspectIdDTO        $id,
        public AspectPathDTO      $path,
        public AspectConfigDTO    $config,
        public AspectMapperDTO    $mapper,
        public AspectDecoratorDTO $decorator,
    ) {
    }

    public function getDriver(): string
    {
        return $this->path->getDriver();
    }

    public function getConfig(): AspectConfigDTO
    {
        return $this->config;
    }

    public function getPathBuilder(): AspectPathBuilder
    {
        return new AspectPathBuilder(
            $this->path,
            $this->mapper,
        );
    }

    public function getPathPatternRegexFilledByAspect(): string
    {
        return $this
            ->getPathBuilder()
            ->fillRegex()
            ->setLang($this->id->lang)
            ->setVersion($this->id->version)
            ->setProduct($this->id->product)
            ->buildRegex()
        ;
    }
}
