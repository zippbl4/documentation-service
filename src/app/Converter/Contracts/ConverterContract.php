<?php

namespace App\Converter\Contracts;

use App\Converter\Attributes\ExpectedType;

interface ConverterContract
{
    /**
     * <pre>
     * public function convert(#[ExpectedType(class: DummyDTO::class)] object $from): FooDTO
     * {
     *     return new FooDTO(
     *        $from->id,
     *        $from->content,
     *     );
     * }
     * </pre>
     *
     * @param object $from
     * @return object
     */
    public function convert(#[ExpectedType()] object $from): object;
}
