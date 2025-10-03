<?php

namespace App\Page\Decorator\Decorators;

use App\Page\Decorator\Contracts\BaseDecorator;

final class VueJSDecorator extends BaseDecorator
{
    public function __construct(
    ) {
    }

    public function handle(string $content, \Closure $next): mixed
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML($content);
        $doc->encoding = 'utf-8';
        $xpath = new \DOMXpath($doc);

        $element = $xpath->query("//body");

        if ($element === false || empty($element[0])) {
            return $content;
        }

        /** @var \DOMElement $element */
        $element = $element[0];
        if ($element->hasAttribute('class')) {
            $class = $element->getAttribute('class');
            $element->setAttribute('class', $class . ' app-vuejs-container');
        } else {
            $element->setAttribute('class', 'app-vuejs-container');
        }

        $element->append(
            $doc->createTextNode(
                $this->renderBladeString("@vite(['resources/css/app.css', 'resources/js/app.js'])")
            ),
        );

        $element = $xpath->query("//footer");
        $element = $element[0];
        $element->append(
            $doc->createTextNode("<example-app></example-app>"),
        );

        $content = $doc->saveHTML();

        return $next($content);
    }
}
