<?php

namespace App\Documentation\Decoration;

use App\Page\Decorator\Contracts\BaseDecorator;

final class IncludesDecorator extends BaseDecorator
{
    public function handle(string $content, \Closure $next): mixed
    {
        $page = $this->getContext()->value('request');

        $content = str_replace(
            '../templates/searchresults.html',
            route('pages.search'),
            $content
        );

        $content = str_replace(
            '../../',
            sprintf('/%s/docs/%s/%s/', $page->lang, $page->product, $page->version),
            $content
        );

        $content = str_replace(
            '../',
            sprintf('/%s/docs/%s/%s/', $page->lang, $page->product, $page->version),
            $content
        );

        return $next($content);
    }
}
