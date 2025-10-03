<?php

namespace App\Page\Decorator;

use App\Dictionary\AliasDictionary;
use App\Page\Decorator\Commands\DecoratorsGetSupported;
use App\Page\Decorator\Contracts\BaseDecorator;
use App\Page\Decorator\Contracts\ContentDecorationInterface;
use App\Page\Decorator\Contracts\SupportedDecoratorsInterface;
use App\Page\Decorator\Decorators\CustomLayoutDecorator;
use App\Page\Decorator\Decorators\GithubToStringDecorator;
use App\Page\Decorator\Decorators\MarkdownToHtmlDecorator;
use App\Page\Decorator\Decorators\RstToHtmlDecorator;
use App\Page\Decorator\Decorators\UserCustomTemplateDecorator;
use App\Page\Decorator\Decorators\VueJSDecorator;
use App\Page\Decorator\Factories\DecoratorFactory;
use App\Page\Decorator\Factories\DecoratorFactoryInterface;
use App\Page\Decorator\Services\ContentDecorationService;
use App\TemplateEngine\Contracts\TemplatesEngineContract;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class DecoratorServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ContentDecorationInterface::class, ContentDecorationService::class);

        $this->registerFactory();
        $this->registerDecorators();
        $this->registerCommand();
    }

    public function registerFactory(): void
    {
        // последующие сервис провайдеры с декораторами должны регистрироваться после провайдера, где биндится фабрика
        $this->app->alias(DecoratorFactoryInterface::class, AliasDictionary::PAGE_DECORATOR);
        $this->app->singleton(DecoratorFactoryInterface::class, DecoratorFactory::class);
        $this->app->singleton(SupportedDecoratorsInterface::class, AliasDictionary::PAGE_DECORATOR);
    }

    public function registerDecorators(): void
    {
        $this->app->afterResolving(BaseDecorator::class, function (BaseDecorator $decorator): void {
            $decorator->setBladeCompiler($this->app->make('blade.compiler'));
            $decorator->setView($this->app->make('view'));
            $decorator->setLogger($this->app->make(LogManager::class));
            $decorator->setTemplatesEngine($this->app->make(TemplatesEngineContract::class));
        });

        $this->app->extend(AliasDictionary::PAGE_DECORATOR, function (DecoratorFactory $manager): DecoratorFactory {
            $manager->addDecorator($this->app->make(GithubToStringDecorator::class));
            $manager->addDecorator($this->app->make(VueJSDecorator::class));
            $manager->addDecorator($this->app->make(CustomLayoutDecorator::class));
            $manager->addDecorator($this->app->make(MarkdownToHtmlDecorator::class));
            $manager->addDecorator($this->app->make(RstToHtmlDecorator::class));
            $manager->addDecorator($this->app->make(UserCustomTemplateDecorator::class));
            return $manager;
        });
    }

    public function registerCommand(): void
    {
        $this->commands([
            DecoratorsGetSupported::class,
        ]);
    }
}
