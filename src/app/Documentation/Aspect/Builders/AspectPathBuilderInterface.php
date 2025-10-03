<?php

namespace App\Documentation\Aspect\Builders;

use App\Documentation\Aspect\DTO\BuiltPathDTO;

interface AspectPathBuilderInterface
{
    public function setLang(string $val): self;
    public function setProduct(string $val): self;
    public function setVersion(string $val): self;
    public function setPage(string $val): self;
    public function setExtension(string $val): self;
    public function buildString(): string;
    public function buildRegex(): string;
    public function buildObject(): BuiltPathDTO;
}
