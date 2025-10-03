<?php

namespace Tests\Unit\Aspect\Builders;

use App\Documentation\Aspect\Builders\AspectPathBuilder;
use App\Documentation\Aspect\DTO\AspectMapperDTO;
use App\Documentation\Aspect\DTO\AspectMapperItemDTO;
use App\Documentation\Aspect\DTO\AspectPathDTO;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Unit\Aspect\BaseAspectTest;

class AspectPathBuilderTest extends BaseAspectTest
{
    #[DataProvider(methodName: 'dataForTestBuildString')]
    public function testBuildString(
        string $expected,
        string $pattern,
        array $data,
    ): void {
        $path = new AspectPathDTO(
            driver: 'local',
            root: $this->app->storagePath('/tests/release/'),
            pattern: $pattern,
        );

        $builder = new AspectPathBuilder($path);

        foreach ($data as $key => $value) {
            $builder->{"set" . ucfirst($key)}($value);
        }

        $this->assertEquals(
            $expected,
            $builder->buildString(),
        );
    }

    #[DataProvider(methodName: 'dataForTestMappedBuildString')]
    public function testMappedBuildString(
        string $expected,
        string $pattern,
        array $mappedData,
        array $data,
    ): void {
        $path = new AspectPathDTO(
            driver: 'local',
            root: $this->app->storagePath('/tests/release/'),
            pattern: $pattern,
        );

        $collect = collect();
        foreach ($mappedData as $mappedDatum) {
            $collect->add(new AspectMapperItemDTO(
                pattern: $mappedDatum['pattern'],
                from: $mappedDatum['from'],
                to: $mappedDatum['to'],
            ));
        }

        $mapper = new AspectMapperDTO(items: $collect);

        $builder = new AspectPathBuilder(
            $path,
            $mapper,
        );

        foreach ($data as $key => $value) {
            $builder->{"set" . ucfirst($key)}($value);
        }

        $this->assertEquals(
            $expected,
            $builder->buildString(),
        );
    }

    #[DataProvider(methodName: 'dataForTestBuildPatternRegex')]
    public function testBuildPatternRegex(
        string $expected,
        string $pattern,
        array $mappedData,
        array $data,
    ): void {
        $path = new AspectPathDTO(
            driver: 'local',
            root: $this->app->storagePath('/tests/release/'),
            pattern: $pattern,
        );

        $collect = collect();
        foreach ($mappedData as $mappedDatum) {
            $collect->add(new AspectMapperItemDTO(
                pattern: $mappedDatum['pattern'],
                from: $mappedDatum['from'],
                to: $mappedDatum['to'],
            ));
        }

        $mapper = new AspectMapperDTO(items: $collect);
        $builder = new AspectPathBuilder(
            $path,
            $mapper,
        );
        $builder = $builder->fillRegex();

        foreach ($data as $key => $value) {
            $builder->{"set" . ucfirst($key)}($value);
        }

        $this->assertEquals(
            $expected,
            $builder->buildRegex(),
        );
    }

    #[DataProvider(methodName: 'dataPatternRegex')]
    public function testPatternRegex(string $subject, array $expected): void
    {
        $path = new AspectPathDTO(
            driver: 'local',
            root: $this->app->storagePath('/tests/release/'),
            pattern: '/{version}/{version}_{lang}/{product}/{page}',
        );

        $builder = new AspectPathBuilder($path);
        preg_match($builder->buildRegex(), $subject, $matches);

        $actual = array_filter([
            'version' => $matches['version'] ?? null,
            'lang' => $matches['lang'] ?? null,
            'product' => $matches['product'] ?? null,
            'page' => $matches['page'] ?? null,
        ]);

        $this->assertEquals($expected, $actual);
    }

    public static function dataForTestBuildString(): iterable
    {
        yield [
            'expected' => '/howto/1.0/ru/{page}.{extension}',
            'pattern' => '/{product}/{version}/{lang}/{page}.{extension}',
            'data' => [
                'lang' => 'ru',
                'product' => 'howto',
                'version' => '1.0',
            ],
        ];

        yield [
            'expected' => '/howto/1.0/ru/index.{extension}',
            'pattern' => '/{product}/{version}/{lang}/{page}.{extension}',
            'data' => [
                'lang' => 'ru',
                'product' => 'howto',
                'version' => '1.0',
                'page' => 'index',
            ],
        ];

        yield [
            'expected' => '/howto/1.0/ru/index.html',
            'pattern' => '/{product}/{version}/{lang}/{page}.{extension}',
            'data' => [
                'lang' => 'ru',
                'product' => 'howto',
                'version' => '1.0',
                'page' => 'index',
                'extension' => 'html',
            ],
        ];

        yield [
            'expected' => '/R2018b/R2018b_rus/matlab/{page}',
            'pattern' => '/{version}/{version}_{lang}/{product}/{page}',
            'data' => [
                'lang' => 'rus',
                'product' => 'matlab',
                'version' => 'R2018b',
            ],
        ];

        yield [
            'expected' => '/symfony-docs-6/{page}',
            'pattern' => '/{product}-docs-{version}/{page}',
            'data' => [
                'product' => 'symfony',
                'version' => '6',
            ],
        ];

        yield [
            'expected' => '/ru/wiki/test',
            'pattern' => '/{lang}/{product}/{page}',
            'data' => [
                'lang' => 'ru',
                'product' => 'wiki',
                'version' => 'wiki',
                'page' => 'test',
                'extension' => '',
            ],
        ];
    }

    public static function dataForTestMappedBuildString(): iterable
    {
        yield [
            'expected' => '/R2018b/R2018b_rus/matlab/{page}',
            'pattern' => '/{version}/{version}_{lang}/{product}/{page}',
            'mappedData' => [[
                'pattern' => '{lang}',
                'from' => 'ru',
                'to' => 'rus',
            ]],
            'data' => [
                'lang' => 'ru',
                'product' => 'matlab',
                'version' => 'R2018b',
            ],
        ];

        yield [
            'expected' => '/R2018b/R2018b_rus/matlab/index.html',
            'pattern' => '/{version}/{version}_{lang}/{product}/{page}',
            'mappedData' => [[
                'pattern' => '{page}',
                'from' => '',
                'to' => 'index.html',
            ]],
            'data' => [
                'lang' => 'rus',
                'product' => 'matlab',
                'version' => 'R2018b',
                'page' => '',
            ],
        ];

        yield [
            'expected' => '/R2018b/R2018b_rus/matlab/index.html',
            'pattern' => '/{version}/{version}_{lang}/{product}/{page}',
            'mappedData' => [[
                'pattern' => '{page}',
                'from' => '',
                'to' => 'index.html',
            ]],
            'data' => [
                'lang' => 'rus',
                'product' => 'matlab',
                'version' => 'R2018b',
                'page' => '',
                'extension' => '',
            ],
        ];

        yield [
            'expected' => '/R2018b/R2018b_rus/matlab/',
            'pattern' => '/{version}/{version}_{lang}/{product}/{page}',
            'mappedData' => [],
            'data' => [
                'lang' => 'rus',
                'product' => 'matlab',
                'version' => 'R2018b',
                'page' => '',
                'extension' => '',
            ],
        ];

        yield [
            'expected' => '/ru/wiki/index.html',
            'pattern' => '/{lang}/{product}/{page}',
            'mappedData' => [],
            'data' => [
                'lang' => 'ru',
                'product' => 'wiki',
                'version' => 'wiki',
                'page' => 'index',
                'extension' => 'html',
            ],
        ];

        yield [
            'expected' => '/ru/wiki/test',
            'pattern' => '/{lang}/{product}/{page}',
            'mappedData' => [],
            'data' => [
                'lang' => 'ru',
                'product' => 'wiki',
                'version' => 'wiki',
                'page' => 'test',
                'extension' => '',
            ],
        ];

        yield [
            'expected' => '/R2018b/R2018b_rus/matlab/../documentation-center.html',
            'pattern' => '/{version}/{version}_{lang}/{product}/{page}',
            'mappedData' => [[
                'pattern' => '{lang}',
                'from' => 'ru',
                'to' => 'rus',
            ], [
                'pattern' => '{page}',
                'from' => 'documentation-center.html',
                'to' => '../documentation-center.html',
            ]],
            'data' => [
                'lang' => 'ru',
                'product' => 'matlab',
                'version' => 'R2018b',
                'page' => 'documentation-center.html',
            ],
        ];

        yield [
            'expected' => '/howto/1.0/rus/index.md',
            'pattern' => '/{product}/{version}/{lang}/{page}.{extension}',
            'mappedData' => [[
                'pattern' => '{lang}',
                'from' => 'ru',
                'to' => 'rus',
            ], [
                'pattern' => '{extension}',
                'from' => 'html',
                'to' => 'md',
            ]],
            'data' => [
                'lang' => 'ru',
                'product' => 'howto',
                'version' => '1.0',
                'page' => 'index',
                'extension' => 'html',
            ],
        ];

        yield [
            'expected' => '/howto/1.0/ru/index.md',
            'pattern' => '/{product}/{version}/{lang}/{page}.{extension}',
            'mappedData' => [[
                'pattern' => '{page}',
                'from' => '',
                'to' => 'index',
            ], [
                'pattern' => '{extension}',
                'from' => '',
                'to' => 'md',
            ]],
            'data' => [
                'lang' => 'ru',
                'product' => 'howto',
                'version' => '1.0',
                'page' => '',
                'extension' => '',
            ],
        ];
    }

    public static function dataForTestBuildPatternRegex(): iterable
    {
        yield [
            'expected' => '~/?(?P<version>\w+)/(?P=version)_(?P<lang>\w+)/(?P<product>\w+)/?(?P<page>.*\.\w+)?$~',
            'pattern' => '/{version}/{version}_{lang}/{product}/{page}',
            'mappedData' => [],
            'data' => [],
        ];

        yield [
            'expected' => '~/?(?P<version>R2018b)/(?P=version)_(?P<lang>rus)/(?P<product>matlab)/?(?P<page>.*\.\w+)?$~',
            'pattern' => '/{version}/{version}_{lang}/{product}/{page}',
            'mappedData' => [],
            'data' => [
                'lang' => 'rus',
                'product' => 'matlab',
                'version' => 'R2018b',
            ],
        ];

        yield [
            'expected' => '~/?(?P<version>R2018b)/(?P=version)_(?P<lang>rus)/(?P<product>\w+)/?(?P<page>.*\.\w+)?$~',
            'pattern' => '/{version}/{version}_{lang}/{product}/{page}',
            'mappedData' => [],
            'data' => [
                'lang' => 'rus',
                'version' => 'R2018b',
            ],
        ];

        yield [
            'expected' => '~/?(?P<version>R2018b)/(?P=version)_(?P<lang>^(rus|eng)$)/(?P<product>matlab)/?(?P<page>.*\.\w+)?$~',
            'pattern' => '/{version}/{version}_{lang}/{product}/{page}',
            'mappedData' => [[
                'pattern' => '{lang}',
                'from' => 'ru',
                'to' => 'rus',
            ], [
                'pattern' => '{lang}',
                'from' => 'en',
                'to' => 'eng',
            ]],
            'data' => [
                'lang' => '^(ru|en)$',
                'product' => 'matlab',
                'version' => 'R2018b',
            ],
        ];
    }

    public static function dataPatternRegex(): iterable
    {
        yield [
            'subject' => '/var/www/storage/tests/release/R2018b/R2018b_rus/matlab/visualize/changing-surface-properties.html',
            'expected' => [
                'version' => 'R2018b',
                'lang' => 'rus',
                'product' => 'matlab',
                'page' => 'visualize/changing-surface-properties.html',
            ],
        ];

        yield [
            'subject' => '/var/www/storage/tests/release/R2018b/R2018b_rus/matlab/visualize',
            'expected' => [],
        ];

        yield [
            'subject' => '   113485  06-30-2023 19:52   R2018b/R2018b_eng/includes/web/css/doc_center.css',
            'expected' => [
                'version' => 'R2018b',
                'lang' => 'eng',
                'product' => 'includes',
                'page' => 'web/css/doc_center.css',
            ],
        ];

        yield [
            'subject' => '/var/www/storage/tests/release/R2018b/R2018b_rus/matlab/index.html',
            'expected' => [
                'version' => 'R2018b',
                'lang' => 'rus',
                'product' => 'matlab',
                'page' => 'index.html',
            ],
        ];

        yield [
            'subject' => '   113485  06-30-2023 19:52   R2018b/R2018b_rus/matlab/index.html',
            'expected' => [
                'version' => 'R2018b',
                'lang' => 'rus',
                'product' => 'matlab',
                'page' => 'index.html',
            ],
        ];

        yield [
            'subject' => '/var/www/storage/tests/release/R2018b/R2018b_rus/matlab/',
            'expected' => [
                'version' => 'R2018b',
                'lang' => 'rus',
                'product' => 'matlab',
            ],
        ];

        yield [
            'subject' => '/var/www/storage/tests/release/R2018b/R2018b_rus/matlab',
            'expected' => [
                'version' => 'R2018b',
                'lang' => 'rus',
                'product' => 'matlab',
            ],
        ];
    }
}
