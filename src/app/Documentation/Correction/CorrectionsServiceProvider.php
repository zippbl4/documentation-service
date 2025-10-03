<?php

namespace App\Documentation\Correction;

use App\Dictionary\AliasDictionary;
use App\Documentation\Correction\Contracts\CorrectionsServiceInterface;
use App\Documentation\Correction\Decorators\CorrectionsDecorator;
use App\Documentation\Correction\Services\CorrectionsService;
use Illuminate\Support\ServiceProvider;

class CorrectionsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CorrectionsServiceInterface::class, CorrectionsService::class);
        $this->registerDecorators();
    }

    public function registerDecorators(): void
    {
        $this->app->extend(AliasDictionary::PAGE_DECORATOR, function (mixed $manager): mixed {
            /** @var $manager \App\Page\Decorator\Factories\DecoratorFactory */
            $manager->addDecorator($this->app->make(CorrectionsDecorator::class));
            return $manager;
        });
    }
}
