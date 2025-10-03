<?php

namespace App\Converter\Examples;

use App\Converter\Attributes\ExpectedType;
use App\Converter\Contracts\ConverterContract;

class DummyDTO2StdClass implements ConverterContract
{
    public function convert(#[ExpectedType(class: DummyDTO::class)] object $from): \stdClass
    {
        $obj = new \stdClass();
        $obj->id = $from->id;
        $obj->content = $from->content;

        return $obj;
    }
}
