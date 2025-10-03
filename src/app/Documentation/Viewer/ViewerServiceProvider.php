<?php

namespace App\Documentation\Viewer;

use App\Dictionary\AliasDictionary;
use App\Documentation\Decoration\AuthDecorator;
use App\Documentation\Decoration\HtmlEntityDecodeDecorator;
use App\Documentation\Decoration\IncludesDecorator;
use App\Documentation\Decoration\NewVacanciesDecorator;
use App\Documentation\Decoration\RecommendationVacanciesDecorator;
use App\Documentation\Decoration\RelativeLinkDecorator;
use App\Documentation\Decoration\RemoveHelpServiceJSDecorator;
use App\Documentation\Decoration\SupportDecorator;
use App\Documentation\Viewer\Contracts\RequestConverterContract;
use App\Documentation\Viewer\Converters\AspectDecoratorDTOConverter;
use App\Documentation\Viewer\Converters\AspectDecoratorDTOConverterInterface;
use App\Documentation\Viewer\Converters\BuiltPageDTOConverter;
use App\Documentation\Viewer\Converters\BuiltPageDTOConverterInterface;
use App\Documentation\Viewer\Converters\RequestConverter;
use App\Documentation\Viewer\Services\PageViewerInterface;
use App\Documentation\Viewer\Services\PageViewerService;
use App\Page\Decorator\Factories\DecoratorFactory;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ViewerServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RequestConverterContract::class, RequestConverter::class);
        $this->app->singleton(BuiltPageDTOConverterInterface::class, BuiltPageDTOConverter::class);
        $this->app->singleton(AspectDecoratorDTOConverterInterface::class, AspectDecoratorDTOConverter::class);
        $this->app->singleton(PageViewerInterface::class, PageViewerService::class);

        $this->registerDecorators();
    }

    public function boot(): void
    {
        $this->bootRoutes();
    }

    public function registerDecorators(): void
    {
        $this->app->extend(AliasDictionary::PAGE_DECORATOR, function (DecoratorFactory $manager): DecoratorFactory {
            $manager->addDecorator($this->app->make(IncludesDecorator::class));
            $manager->addDecorator($this->app->make(SupportDecorator::class));
            $manager->addDecorator($this->app->make(AuthDecorator::class));
            $manager->addDecorator($this->app->make(HtmlEntityDecodeDecorator::class));
            $manager->addDecorator($this->app->make(RemoveHelpServiceJSDecorator::class));
            $manager->addDecorator($this->app->make(NewVacanciesDecorator::class));
            $manager->addDecorator($this->app->make(RecommendationVacanciesDecorator::class));
            $manager->addDecorator($this->app->make(RelativeLinkDecorator::class));

            return $manager;
        });
    }

    public function bootRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
    }
}
