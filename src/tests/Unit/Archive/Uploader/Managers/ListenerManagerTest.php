<?php

namespace Tests\Unit\Archive\Uploader\Managers;

use App\Documentation\Aspect\Contracts\AspectServiceContract;
use App\Documentation\Aspect\DTO\AspectConfigDTO;
use App\Documentation\Aspect\DTO\AspectDecoratorDTO;
use App\Documentation\Aspect\DTO\AspectDTO;
use App\Documentation\Aspect\DTO\AspectIdDTO;
use App\Documentation\Aspect\DTO\AspectMapperDTO;
use App\Documentation\Aspect\DTO\AspectPathDTO;
use App\Documentation\AspectPlugin\PageDriver\Handlers\DatabaseSaverHandler;
use App\Documentation\Researcher\Services\ResearcherService;
use App\Documentation\Searcher\Listeners\OnProductUploadedRunIndexerJob;
use App\Documentation\Uploader\Events\ProductUploaded;
use App\SearchEngine\Contracts\ProductSearchServiceInterface;
use Mockery\MockInterface;
use Tests\TestCase;

class ListenerManagerTest extends TestCase
{
    public function testRun(): void
    {
        $config = app(\Illuminate\Contracts\Config\Repository::class);
        $config->set('app-config.release_folder', $this->app->storagePath('/tests/release/'));

        $searchServiceMock = $this->mock(ProductSearchServiceInterface::class, function (MockInterface $mock) {
            $mock
                ->shouldReceive('index')
                ->atLeast()->once()
            ;
        });

        $this->app->instance(ProductSearchServiceInterface::class, $searchServiceMock);

        $aspectMock = $this->mock(AspectServiceContract::class, function (MockInterface $mock) {
            $mock
                ->shouldReceive('getAspect')
                ->once()
                ->andReturn(new AspectDTO(
                    id: new AspectIdDTO(
                        lang: 'rus|eng',
                        product: 'matlab',
                        version: 'R2018b',
                    ),
                    path: new AspectPathDTO(
                        driver: 'local',
                        root: $this->app->storagePath('/tests/release/'),
                        pattern: '/{version}/{version}_{lang}/{product}/{page}',
                    ),
                    config: new AspectConfigDTO(
                        finderProductPageExtension: null,
                        finderProductLang: null,
                    ),
                    mapper: new AspectMapperDTO(collect()),
                    decorator: new AspectDecoratorDTO(collect()),
                ))
            ;
        });

        $this->app->instance(AspectServiceContract::class, $aspectMock);

        $this
            ->app
            ->make(ResearcherService::class)
            ->handlePages(
                $this->app->make(DatabaseSaverHandler::class),
                $this->app->make(OnProductUploadedRunIndexerJob::class),
                new ProductUploaded(
                    aspectId: -1,
                    jobUuid: 'dummy',
                    productPath: app()->storagePath('/tests/release/R2018b'),
                ),
        );
    }
}
