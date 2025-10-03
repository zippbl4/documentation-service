<?php

namespace App\ContentEngine\Contracts;

interface RendererInterface
{
    public function render(string $text, array $data = []): string;
}
