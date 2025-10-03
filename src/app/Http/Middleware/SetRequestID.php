<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Ramsey\Uuid\Uuid;

final readonly class SetRequestID
{
    public function __construct(private LogManager $logger)
    {
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @see https://laravel.su/docs/10.x/logging#kontekstnaia-informaciia
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        $requestId = Uuid::uuid4()->toString();

        $this->logger->shareContext([
            'x-request-id' => $requestId,
        ]);

        $response = $next($request);
        $response->headers->set('X-Request-ID', $requestId);

        return $response;
    }
}
