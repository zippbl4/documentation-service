<?php

namespace App\AsyncTask\Services;

use App\AsyncTask\Contracts\HandlerClassStringContract;

final readonly class HandlerClassString implements HandlerClassStringContract
{
    public function parseHandlerString(string $handler): array
    {
        // App\AsyncTask\Handlers\ReceiveHandler1:{"productPath":1,"getConfig":1,"getPathPatternRegexFilledByAspect":1}
        [$handler, $parameters] = array_pad(explode(':', $handler, 2), 2, []);

        if (is_string($parameters)) {
            $parameters = json_decode($parameters, associative: true);
        }

        return [$handler, $parameters];
    }

    public function createHandlerString(string|object $handler, array $params = []): string
    {
        if (! is_string($handler)) {
            $handler = $handler::class;
        }

        return sprintf(
            '%s:%s',
            $handler,
            json_encode($params, flags: JSON_UNESCAPED_SLASHES)
        );
    }
}
