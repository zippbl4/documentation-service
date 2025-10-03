<?php

namespace Tests\Unit\Aspect\DTO;

use App\Documentation\Aspect\DTO\AspectIdDTO;
use Tests\TestCase;

class AspectIdDTOTest extends TestCase
{
    /**
     * @dataProvider matchDataProvider
     */
    public function testMatch(AspectIdDTO $currentAspectId, AspectIdDTO $storedAspectId, bool $isMatched): void
    {
        $this->assertEquals(
            $isMatched,
            $storedAspectId->match($currentAspectId)
        );
    }

    public function matchDataProvider(): iterable
    {
        yield [
            'current' => new AspectIdDTO(
                lang: 'rus',
                product: 'matlab',
                version: 'R2018b',
            ),
            'stored' => new AspectIdDTO(
                lang: 'rus|eng',
                product: 'matlab',
                version: 'R2018b',
            ),
            'isMatched' => true,
        ];

        yield [
            'current' => new AspectIdDTO(
                lang: 'rus',
                product: 'matlab',
                version: 'R2018b',
            ),
            'stored' => new AspectIdDTO(
                lang: 'rus|eng',
                product: 'matlab',
                version: '.*',
            ),
            'isMatched' => true,
        ];

        yield [
            'current' => new AspectIdDTO(
                lang: 'rus',
                product: 'matlab',
                version: 'R2018111',
            ),
            'stored' => new AspectIdDTO(
                lang: '[rus|eng]+',
                product: 'matlab',
                version: 'R2018b',
            ),
            'isMatched' => false,
        ];
    }
}
