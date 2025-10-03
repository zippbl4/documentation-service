<?php

namespace App\ContentEngine\Renders;

use App\ContentEngine\Contracts\RendererInterface;
use Doctrine\RST\Parser;

class RstRendererService implements RendererInterface
{
    public function render(string $text, array $data = []): string
    {
        return (new Parser())->parse($text)->renderDocument();
    }
}
