<?php

namespace App\SearchEngine;

use App\SearchEngine\Contracts\ProductSearchServiceInterface;
use App\SearchEngine\Services\ProductElasticSearchService;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;

class SearchEngineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerElasticSearchEngine();
        $this->registerSearchEngines();
        $this->registerContextualBinding();
    }

    public function boot(Dispatcher $events): void
    {
        $this->bootEvents($events);
    }

    public function registerElasticSearchEngine(): void
    {
        $this->app->bind(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts([$this->app->make('config')->get('services.elastic.domain')])
                ->setLogger($this->app->make(LogManager::class)->channel('elastic'))
                ->setTracer($this->app->make(LogManager::class)->channel('elastic_trace'))
                ->build();
        });
    }

    public function registerSearchEngines(): void
    {
        $this->app->bind(ProductSearchServiceInterface::class, ProductElasticSearchService::class);
    }

    public function bootEvents(Dispatcher $events): void
    {
    }

    public function registerContextualBinding(): void
    {
    }
}
