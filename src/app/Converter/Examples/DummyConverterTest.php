<?php

namespace App\Converter\Examples;

use App\Converter\Contracts\ConverterServiceContract;
use Tests\TestCase;

class DummyConverterTest extends TestCase
{
    public function testConvertDummyDTO2StdClass(): void
    {
        $converter = $this->app->make(ConverterServiceContract::class);
        $object = $converter->convert($expected = new DummyDTO(1, 'content'), \stdClass::class);

        $this->assertInstanceOf(\stdClass::class, $object);
    }

    public function testConvertDummyDTO2FooDTO(): void
    {
        $converter = $this->app->make(ConverterServiceContract::class);
        $object = $converter->convert(new DummyDTO(1, 'content'), FooDTO::class);

        $this->assertInstanceOf(FooDTO::class, $object);
    }
}
