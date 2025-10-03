<?php

namespace App\Gateway\Contracts;

use App\Gateway\Exceptions\HttpClientException;

interface HttpClientInterface
{
    /**
     * @throws HttpClientException
     */
    public function handle(RequestInterface $request): ResponseInterface;
}
