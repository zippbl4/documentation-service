<?php

namespace App\Documentation\Decoration;

use App\Page\Decorator\Contracts\BaseDecorator;
use Ramsey\Uuid\Uuid;

/** @deprecated  */
final class BodyDecorator extends BaseDecorator
{
    public function handle(string $content, \Closure $next): mixed
    {
        $uuid = Uuid::uuid4()->toString();
        $tag1 = "<!--fast_edit_beg_$uuid-->";
        $tag2 = "<!--fast_edit_end_$uuid-->";
        $content = str_replace(
            "</body>",
            "$tag1$tag2\n</body>",
            $content
        );

        $body = $this->renderView('decorators.script');

        $content = preg_replace(
            "#$tag1(.*)$tag2#",
            "$tag1\n$body\n$tag2",
            $content
        );

        return $next($content);
    }
}
