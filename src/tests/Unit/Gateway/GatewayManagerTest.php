<?php

namespace Tests\Unit\Gateway;

use App\Gateway\Services\GatewayService;
use App\ObjectMapper\Contracts\JsonDeserializerInterface;
use Tests\TestCase;

class GatewayManagerTest extends TestCase
{
    public function test1()
    {
        $endpointsManager = new GatewayService(
            new DummyHttpHttpClient(),
            $this->app->get(JsonDeserializerInterface::class)
        );

        /** @var DummyResponse $result */
        $actual = $endpointsManager->endpoint(
            new DummyRequest(),
            DummyResponse::class
        );

        $expected = new DummyResponse(
            result: true,
            response: [],
            errors: null
        );

        $this->assertEquals($expected, $actual);
    }
}
