<?php

namespace App\ObjectMapper;

use App\ObjectMapper\Contracts\ArrayDeserializerInterface;
use App\ObjectMapper\Contracts\JsonDeserializerInterface;
use App\ObjectMapper\Contracts\JsonSerializerInterface;
use App\ObjectMapper\Services\ArraySerializer;
use App\ObjectMapper\Services\JsonSerializer;
use Doctrine\Common\Annotations\AnnotationReader;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class ObjectMapperServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SymfonySerializer::class, function (): SymfonySerializer {
            $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
            $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
            $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);

            $normalizers = [
                new BackedEnumNormalizer(),
                new ObjectNormalizer(
                    classMetadataFactory: $classMetadataFactory,
                    nameConverter: $metadataAwareNameConverter,
                    propertyTypeExtractor: $extractor,
                ),
                new ArrayDenormalizer(),
            ];

            $encoders = [
                new JsonEncoder(),
            ];

            return new SymfonySerializer($normalizers, $encoders);
        });

        $this->app->bind(JsonDeserializerInterface::class, JsonSerializer::class);
        $this->app->bind(JsonSerializerInterface::class, JsonSerializer::class);

        $this->app->bind(ArrayDeserializerInterface::class, ArraySerializer::class);
    }
}
