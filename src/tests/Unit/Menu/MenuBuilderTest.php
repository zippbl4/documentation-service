<?php

namespace Unit\Menu;

use Symfony\Component\Finder\Finder;
use Tests\TestCase;

class MenuBuilderTest extends TestCase
{
    public function test1(): void
    {
        /**
         * @see \Tests\Unit\Aspect\Builders\AspectPathBuilderTest::dataForTestBuildPatternRegex
         */
        $pathPatternRegex = '~/?(?P<version>R2018b)/(?P=version)_(?P<lang>rus)/(?P<product>matlab)/?(?P<page>.*\.\w+)?$~';
        $path = $this->app->storagePath('/tests/release/R2018b');

        $finder = (new Finder())
            ->in($path)
            ->name('*.html')
            ->files();

        foreach ($finder as $file) {
            preg_match($pathPatternRegex, $file->getPathname(), $matches);

            if (empty($matches['page'])) {
                continue;
            }

            $this->f($matches);
        }

    }

    private function f(array $matches)
    {
        [
            'version' => $version,
            'lang' => $lang,
            'product' => $product,
            'page' => $page,
        ] = $matches;


        $explodedPage = explode('/', $page);

        $root = array_shift($explodedPage);
        if (empty($explodedPage)) {

        }

        dd($explodedPage, $root);
    }
}
