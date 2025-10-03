<?php

namespace App\Converter;

use App\Converter\Attributes\ExpectedType;
use App\Converter\Contracts\ConverterContract;
use App\Converter\Contracts\ConverterServiceContract;
use App\Converter\Factory\ConverterFactory;
use App\Converter\Services\ConverterService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ConverterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ConverterServiceContract::class, ConverterService::class);

        $this->registerConverters();
    }

    public function registerConverters(): void
    {
        $this->app->extend(ConverterFactory::class, function (ConverterFactory $factory): ConverterFactory {
            $classes = include $this->app->basePath('vendor/composer/autoload_classmap.php');
            $classes = array_filter($classes, function (string $className) {
                return Str::startsWith($className, 'App');
            }, ARRAY_FILTER_USE_KEY);

            foreach ($classes as $className => $unused) {
                try {
                    $class = new \ReflectionClass($className);
                } catch (\Throwable|\ReflectionException $e) {
                    continue;
                }

                if ($class->isAbstract() || $class->isInterface()) {
                    continue;
                }

                if (! $class->implementsInterface(ConverterContract::class)) {
                    continue;
                }

                $method = $class->getMethod('convert');

                $parameters = $method->getParameters();
                $parameter = current($parameters);

                $attributes = $parameter->getAttributes(ExpectedType::class);
                $attribute = current($attributes);

                $arguments = $attribute->getArguments();
                $argument = current($arguments);

                $return = $method
                    ->getReturnType()
                    ->getName()
                ;

                $factory->addConverter($argument, $return, $this->app->make($className));
            }

            return $factory;
        });
    }
}
