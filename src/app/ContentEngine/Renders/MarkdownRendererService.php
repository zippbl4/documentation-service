<?php

namespace App\ContentEngine\Renders;

use App\ContentEngine\Contracts\RendererInterface;
use Illuminate\Mail\Markdown;

class MarkdownRendererService implements RendererInterface
{
    public function render(string $text, array $data = []): string
    {
        return Markdown::parse($text);
    }
}
