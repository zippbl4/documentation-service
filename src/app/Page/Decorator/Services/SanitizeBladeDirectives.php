<?php

namespace App\Page\Decorator\Services;

class SanitizeBladeDirectives
{
    protected array $allowedDirectives = [
        '@if',
        '@endif',
        '@foreach',
        '@endforeach',
        '@include',
        '@extends',
        '@section',
        '@endsection',
        '@yield',
    ];

    public function sanitizeBlade(string $content): string
    {
        $pattern = '/@[\w]+/';

        return preg_replace_callback($pattern, function ($matches) {
            $directive = $matches[0];
            if (!in_array($directive, $this->allowedDirectives)) {
                return '';
            }
            return $directive;
        }, $content);
    }
}
