<?php

namespace App\Page\Decorator\Decorators;

use App\Page\Decorator\Contracts\BaseDecorator;

final class GithubToStringDecorator extends BaseDecorator
{
    public function handle(string $content, \Closure $next): mixed
    {
        $response = json_decode($content, true);
        $content = $response['content'];
        $content = base64_decode($content);
        $content = nl2br($content);

        return $next($content);
    }
}
