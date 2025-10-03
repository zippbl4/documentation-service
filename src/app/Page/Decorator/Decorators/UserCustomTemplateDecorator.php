<?php

namespace App\Page\Decorator\Decorators;

use App\Page\Decorator\Contracts\BaseDecorator;
use App\Page\Decorator\Contracts\HasUserInputDecoratorInterface;
use App\Page\Decorator\Services\SanitizeBladeDirectives;

class UserCustomTemplateDecorator extends BaseDecorator implements HasUserInputDecoratorInterface
{
    public function __construct(private SanitizeBladeDirectives $sanitizer)
    {
    }

    public function handle(string $content, \Closure $next): mixed
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML($content);
        $doc->encoding = 'utf-8';
        $xpath = new \DOMXpath($doc);

        $content = $this->renderBladeString(
            $this->sanitizer->sanitizeBlade($this->getUserCustomTemplate()),
            [
                'content' => $content,
                'doc' => $doc,
                'xpath' => $xpath,
                'ctx' => $this->getContext(),
            ]
        );

        return $next($content);
    }
}
