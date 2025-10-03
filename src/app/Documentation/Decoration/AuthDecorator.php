<?php

namespace App\Documentation\Decoration;

use App\Page\Decorator\Contracts\BaseDecorator;
use Ramsey\Uuid\Uuid;

final class AuthDecorator extends BaseDecorator
{
    public function handle(string $content, \Closure $next): mixed
    {
        $uuid = Uuid::uuid4()->toString();

        $doc = new \DOMDocument();
        @$doc->loadHTML($content);
        $doc->encoding = 'utf-8';

        $xpath = new \DOMXpath($doc);
        $element = $xpath->query("//div[contains(@class, 'search_nested_content_container')]");
        if ($element !== false && isset($element[0])) {
            /** @var \DOMElement $element */
            $element = $element[0];

            $element->append(
                $doc->createComment("fast_edit_beg_$uuid"),
                $doc->createTextNode($this->renderView('decorators.auth')),
                $doc->createComment("fast_edit_end_$uuid"),
            );

            $content = $doc->saveHTML();
        }

        return $next($content);
    }
}
