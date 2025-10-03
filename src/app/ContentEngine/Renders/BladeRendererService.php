<?php

namespace App\ContentEngine\Renders;

use App\ContentEngine\Contracts\RendererInterface;
use Illuminate\View\Compilers\BladeCompiler;

class BladeRendererService implements RendererInterface
{
    public function render(string $text, $data = []): string
    {
        return BladeCompiler::render($text, $data);
    }
}
