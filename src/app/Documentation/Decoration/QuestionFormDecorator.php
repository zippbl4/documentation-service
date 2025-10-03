<?php

namespace App\Documentation\Decoration;

use App\Page\Decorator\Contracts\BaseDecorator;

/** @deprecated  */
final class QuestionFormDecorator extends BaseDecorator
{
    public function handle(string $content, \Closure $next): mixed
    {
        $content = preg_replace(
            '/(bootstrap\.min\.js[^>]+><\/script>)/',
            "$1\n" . $this->renderView('v1.partials.question-form-head'),
            $content
        );

        $content = str_replace(
            '</body>',
            $this->renderView('v1.partials.question-form') . '</body>',
            $content
        );

        return $next($content);
    }
}
