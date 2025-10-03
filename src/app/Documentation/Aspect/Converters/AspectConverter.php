<?php

namespace App\Documentation\Aspect\Converters;

use App\Documentation\Aspect\Contracts\AspectConverterInterface;
use App\Documentation\Aspect\DTO\AspectDecoratorDTO;
use App\Documentation\Aspect\DTO\AspectDecoratorItemDTO;
use App\Documentation\Aspect\DTO\AspectDTO;
use App\Documentation\Aspect\DTO\AspectIdDTO;
use App\Documentation\Aspect\DTO\AspectMapperDTO;
use App\Documentation\Aspect\DTO\AspectMapperItemDTO;
use App\Documentation\Aspect\DTO\AspectPathDTO;
use App\Documentation\Aspect\DTO\AspectConfigDTO;
use App\Documentation\Aspect\Entities\Aspect;
use App\Documentation\Aspect\Entities\Decorator;
use App\Documentation\Aspect\Entities\Mapper;
use App\Documentation\Aspect\Entities\Validation;
use App\ObjectMapper\Contracts\ArrayDeserializerInterface;
use Illuminate\Contracts\Config\Repository as Config;

final readonly class AspectConverter implements AspectConverterInterface
{
    public function __construct(
        private Config                     $config,
        private ArrayDeserializerInterface $deserializer,
    ) {
    }

    public function convertToAspect(Aspect $aspect): AspectDTO
    {
        return new AspectDTO(
            entityId: $aspect->id,
            id: $this->convertToAspectId($aspect),
            path: $this->convertToAspectPath($aspect),
            config: $this->convertToConfig($aspect),
            mapper: $this->convertToMapper($aspect),
            decorator: $this->convertToDecorator($aspect),
        );
    }

    public function convertToAspectId(Aspect $aspect): AspectIdDTO
    {
        return new AspectIdDTO(
            lang: $aspect->lang,
            product: $aspect->product,
            version: $aspect->version,
        );
    }

    public function convertToAspectPath(Aspect $aspect): AspectPathDTO
    {
        $root = $aspect->getPath()->root;
        if ($this->config->has($root)) {
            $root = $this->config->get($root);
        }

        return new AspectPathDTO(
            driver: $aspect->getPath()->driver,
            root: $root,
            pattern: $aspect->getPath()->pattern,
        );
    }

    public function convertToDecorator(Aspect $aspect): AspectDecoratorDTO
    {
        return new AspectDecoratorDTO(
            items: $aspect
                ->getDecorators()
                ->map(fn (Decorator $item) => new AspectDecoratorItemDTO(
                    name: $item->name,
                    userCustomTemplate: $item->user_custom_template,
                )),
        );
    }

    public function convertToMapper(Aspect $aspect): AspectMapperDTO
    {
        return new AspectMapperDTO(
            items: $aspect
                ->getMappers()
                ->map(fn (Mapper $item) => new AspectMapperItemDTO(
                    pattern: $item->pattern,
                    from: $item->map_from,
                    to: $item->map_to,
                )),
        );
    }

    public function convertToConfig(Aspect $aspect): AspectConfigDTO
    {
        return $this->deserializer->deserialize(
            $aspect
                ->getSettingsWithCasts()
                ->pluck('value', 'name')
                ->toArray(),
            AspectConfigDTO::class,
        );
    }

    public function convertToValidations(Aspect $aspect): array
    {
        return $aspect
            ->getValidations()
            ->map(fn (Validation $item) => $item->name)
            ->toArray()
        ;
    }
}
