<?php

namespace Tests\Unit\Gateway;

use App\Gateway\Contracts\HttpClientInterface;
use App\Gateway\Contracts\RequestInterface;
use App\Gateway\Contracts\ResponseInterface;
use App\Gateway\DTO\Response;

class DummyHttpHttpClient implements HttpClientInterface
{
    public function handle(RequestInterface $request): ResponseInterface
    {
        return new Response(
            statusCode: 200,
            response: file_get_contents(__DIR__ . '/dummy.json')
        );
    }
}
