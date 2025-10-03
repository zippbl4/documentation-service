<?php

namespace App\Documentation\Decoration;

use App\Page\Decorator\Contracts\BaseDecorator;
use Ramsey\Uuid\Uuid;

/** @deprecated  */
final class GoogleTagManagerDecorator extends BaseDecorator
{
    public function handle(string $content, \Closure $next): mixed
    {
        $uuid = Uuid::uuid4()->toString();
        $tag1 = "<!--fast_edit_beg_$uuid-->";
        $tag2 = "<!--fast_edit_end_$uuid-->";
        $body = $this->renderView('decorators.google_tag_manager');

        $content = str_replace(
            "</head>",
            "$tag1$tag2\n</head>",
            $content
        );

        $content = preg_replace(
            "#$tag1(.*)$tag2#",
            "$tag1\n$body\n$tag2",
            $content
        );

        return $next($content);
    }
}
