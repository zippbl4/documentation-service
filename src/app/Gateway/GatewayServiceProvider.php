<?php

namespace App\Gateway;

use App\Gateway\Contracts\GatewayAuthenticatableInterface;
use App\Gateway\Contracts\GatewayInterface;
use App\Gateway\Contracts\HttpClientInterface;
use App\Gateway\Services\DummyGatewayAuthenticatableService;
use App\Gateway\Services\GatewayService;
use App\Gateway\Services\HttpClient;
use Carbon\Laravel\ServiceProvider;
use Illuminate\Log\Logger;
use Psr\Log\LoggerInterface;

class GatewayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->bind(GatewayAuthenticatableInterface::class, DummyGatewayAuthenticatableService::class);
        } else {
            $this->app->bind(GatewayAuthenticatableInterface::class, GatewayService::class);
        }
        $this->app->bind(HttpClientInterface::class, HttpClient::class);
        $this->app->bind(GatewayInterface::class, GatewayService::class);

        $this->app
            ->when([HttpClient::class])
            ->needs(LoggerInterface::class)
            ->give(Logger::class);
    }
}
