<?php

namespace App\Documentation\Decoration;

use App\Page\Decorator\Contracts\BaseDecorator;
use Ramsey\Uuid\Uuid;

final class SupportDecorator extends BaseDecorator
{
    public function handle(string $content, \Closure $next): mixed
    {
        $uuid = Uuid::uuid4()->toString();

        $doc = new \DOMDocument();
        @$doc->loadHTML($content);
        $doc->encoding = 'utf-8';

        $xpath = new \DOMXpath($doc);
        $element = $xpath->query("//ul[contains(@id, 'topnav_main')]");
        if ($element !== false && isset($element[0])) {
            /** @var \DOMElement $element */
            $element = $element[0];

            $element->appendChild($doc->createComment("fast_edit_beg_$uuid"));
            $element->appendChild($doc->createTextNode($this->renderView('decorators.support')));
            $element->appendChild($doc->createComment("fast_edit_end_$uuid"));

            $content = $doc->saveHTML();
        }

        return $next($content);
    }
}
