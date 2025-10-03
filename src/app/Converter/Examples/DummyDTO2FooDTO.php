<?php

namespace App\Converter\Examples;

use App\Converter\Attributes\ExpectedType;
use App\Converter\Contracts\ConverterContract;

class DummyDTO2FooDTO implements ConverterContract
{
    public function convert(#[ExpectedType(class: DummyDTO::class)] object $from): FooDTO
    {
        return new FooDTO(
            $from->id,
            $from->content,
        );
    }
}
