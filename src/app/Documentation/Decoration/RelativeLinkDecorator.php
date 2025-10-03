<?php

namespace App\Documentation\Decoration;

use App\Dictionary\ContextDictionary;
use App\Page\Decorator\Contracts\BaseDecorator;

/**
 * Из:
 * <img src="documentation.aspect.png" alt="documentation.aspect.png">
 * В:
 * <img src="/%s/docs/%s/%s/documentation.aspect.png" alt="documentation.aspect.png">
 */
final class RelativeLinkDecorator extends BaseDecorator
{
    public function handle(string $content, \Closure $next): mixed
    {
        $page = $this->getContext()->value(ContextDictionary::PAGE_REQUEST_DTO);

        $doc = new \DOMDocument();
        // https://ru.stackoverflow.com/questions/514089/php-domdocument-savehtml-%D0%B2%D1%8B%D0%B2%D0%BE%D0%B4%D0%B8%D1%82-%D0%B2-%D0%BD%D0%B5%D0%BF%D0%BE%D0%BD%D1%8F%D1%82%D0%BD%D0%BE%D0%B9-%D0%BA%D0%BE%D0%B4%D0%B8%D1%80%D0%BE%D0%B2%D0%BA%D0%B5
        @$doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        $doc->encoding = 'utf-8';

        $xpath = new \DOMXpath($doc);
        $elements = $xpath->query("//img");

        /** @var \DOMElement $element */
        foreach ($elements as $element) {
            $img = $element->getAttribute('src');
            $element->setAttribute('src', sprintf('/%s/docs/%s/%s/%s', $page->lang, $page->product, $page->version, $img));
        }

        $content = $doc->saveHTML();
        return $next($content);
    }
}
