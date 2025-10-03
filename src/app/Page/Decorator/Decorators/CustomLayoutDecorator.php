<?php

namespace App\Page\Decorator\Decorators;

use App\Page\Decorator\Contracts\BaseDecorator;
use DOMDocument;
use DOMXPath;

final class CustomLayoutDecorator extends BaseDecorator
{
    public function __construct(
    ) {
    }

    public function handle(string $content, \Closure $next): mixed
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($content);
        $doc->encoding = 'utf-8';

        $content = $this->renderView('pages.show', [
            'content' => $content,
        ]);

        return $next($content);
    }

    private function getBody(DOMDocument $doc): ?string
    {
        $xpath = new DOMXpath($doc);

        $queries = [
            // TODO передать
            "//div[contains(@id, 'content_container')]",
            "//div[contains(@class, 'wy-nav-content')]",
            "//body",
        ];

        foreach ($queries as $query) {
            $element = $xpath->query($query);
            if ($element !== false && isset($element[0])) {
                break;
            }
        }

        if ($element === false || empty($element[0])) {
            return null;
        }

        return $doc->saveHTML($element[0]);
    }

    private function getScripts(DOMDocument $doc): ?string
    {
        $xpath = new DOMXpath($doc);

        $queries = [
            "//script[@src]",
            "//script[string-length(text()) > 1]",
        ];

        $scripts = '';
        foreach ($queries as $query) {
            $elements = $xpath->query($query);
            foreach($elements as $element) {
                $scripts .= $doc->saveHTML($element) . PHP_EOL;
            }
        }

        return $scripts;
    }

    private function getStyles(DOMDocument $doc): ?string
    {
        $xpath = new DOMXpath($doc);

        $queries = [
            "//link[@href]",
            "//style[string-length(text()) > 1]",
        ];

        $content = '';
        foreach ($queries as $query) {
            $elements = $xpath->query($query);
            foreach($elements as $element) {
                $content .= $doc->saveHTML($element) . PHP_EOL;
            }
        }

        return $content;
    }
}
