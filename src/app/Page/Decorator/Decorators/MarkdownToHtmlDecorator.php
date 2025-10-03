<?php

namespace App\Page\Decorator\Decorators;

use App\Page\Decorator\Contracts\BaseDecorator;

final class MarkdownToHtmlDecorator extends BaseDecorator
{

    public function handle(string $content, \Closure $next): mixed
    {
        return $next($this->renderMarkdownString($content));
    }
}
