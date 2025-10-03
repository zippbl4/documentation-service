<?php

namespace Test\Unit\Archive\Researcher;

use App\Archive\Researcher\DTO\ProductDTO;
use App\Archive\Researcher\Strategies\ZipManualResearcherStrategy;
use Tests\TestCase;

class ResearcherTest extends TestCase
{
    public function testGetProductInfo(): void
    {
        $regex = '~/?(?P<version>R2018b)/(?P=version)_(?P<lang>rus|eng)/(?P<product>matlab)/?(?P<page>.*\.\w+)?$~';
        $archive = $this->app->storagePath('/tests/zip/R2018b.zip');

        $strategy = new ZipManualResearcherStrategy();

        $actual = $strategy->getProductInfo(
            $archive,
            $regex,
        );

        $expected = [
            new ProductDTO(
                'rus',
                'matlab',
                'R2018b',
            ),
            new ProductDTO(
                'eng',
                'matlab',
                'R2018b',
            )
        ];

        $this->assertEqualsCanonicalizing(
            $expected,
            $actual,
        );
    }
}
