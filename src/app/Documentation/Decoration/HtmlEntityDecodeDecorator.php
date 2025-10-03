<?php

namespace App\Documentation\Decoration;

use App\Page\Decorator\Contracts\BaseDecorator;

final class HtmlEntityDecodeDecorator extends BaseDecorator
{
    public function handle(string $content, \Closure $next): mixed
    {
        return $next(html_entity_decode($content));
    }
}
