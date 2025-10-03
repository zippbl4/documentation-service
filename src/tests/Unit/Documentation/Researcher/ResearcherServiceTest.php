<?php

namespace Tests\Unit\Documentation\Researcher;

use App\Documentation\Aspect\Contracts\AspectServiceContract;
use App\Documentation\Aspect\DTO\AspectConfigDTO;
use App\Documentation\Aspect\DTO\AspectDecoratorDTO;
use App\Documentation\Aspect\DTO\AspectDTO;
use App\Documentation\Aspect\DTO\AspectIdDTO;
use App\Documentation\Aspect\DTO\AspectMapperDTO;
use App\Documentation\Aspect\DTO\AspectPathDTO;
use App\Documentation\Researcher\Services\ResearcherService;
use App\AsyncTask\Receive\ReceiveFromChannelJob;
use App\AsyncTask\Send\SendToChannelJob;
use Mockery\MockInterface;
use Tests\TestCase;

class ResearcherServiceTest extends TestCase
{
    protected string $channel = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockAspectService();
        $this->channel = 'test1';
    }

    public function testReadFiles(): void
    {
        $service = $this->app->make(ResearcherService::class);
        $service->readFiles(new SendToChannelJob(
            channel: $this->channel,
            aspectId: 1,
            productPath: $this->app->storagePath('/tests/release/R2018b'),
        ));
    }

    public function testHandleFiles(): void
    {
        $service = $this->app->make(ResearcherService::class);
        $service->handleFiles(new ReceiveFromChannelJob(
            channel: $this->channel,
        ));
    }

    private function mockAspectService(): void
    {
        $aspect = new AspectDTO(
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
        );

        $mock = $this->mock(AspectServiceContract::class, function (MockInterface $mock) use ($aspect) {
            $mock
                ->shouldReceive('getAspect')->andReturn($aspect)
            ;
        });

        $this->app->instance(AspectServiceContract::class, $mock);
    }
}
