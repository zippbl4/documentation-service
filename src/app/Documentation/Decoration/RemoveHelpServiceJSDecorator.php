<?php

namespace App\Documentation\Decoration;

use App\Page\Decorator\Contracts\BaseDecorator;

final class RemoveHelpServiceJSDecorator extends BaseDecorator
{
    public function handle(string $content, \Closure $next): mixed
    {
        $content = str_replace(
            '<script xmlns="http://www.w3.org/1999/xhtml" src="../includes/shared/scripts/helpservices.js"></script>',
            '',
            $content
        );

        return $next($content);
    }
}
