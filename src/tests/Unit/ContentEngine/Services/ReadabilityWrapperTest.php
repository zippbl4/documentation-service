<?php

namespace Tests\Unit\ContentEngine\Services;

use App\ContentEngine\Readability\ReadabilityJSWrapper;
use Tests\TestCase;

class ReadabilityWrapperTest extends TestCase
{
    public function testRender(): void
    {
        $html = file_get_contents(__DIR__ . '/matlab_external-writing-and-reading-data.html.html');

        $readability = $this->app->make(ReadabilityJSWrapper::class);
        $parsed = $readability->parse($html);
        dd($parsed);
    }
}
