<?php

namespace App\AsyncTask\Tests;

use App\AsyncTask\Contracts\Handlers\SenderHandler;
use App\AsyncTask\Services\HandlerClassString;
use App\Documentation\Uploader\Handlers\ProductUploadedSenderHandler;
use Tests\TestCase;

class HandlerClassStringTest extends TestCase
{

    private HandlerClassString $handlerClassString;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handlerClassString = $this->app->make(HandlerClassString::class);
    }

    public function testCreateHandleString()
    {
       $actual = $this
           ->handlerClassString
           ->createHandlerString(ProductUploadedSenderHandler::class, ['aspectId' => 1, 'productPath' => $this->app->storagePath('/tests/releases/R2018b')])
       ;

       $this->assertEquals(
           ProductUploadedSenderHandler::class . ':' . '{"aspectId":1,"productPath":"/var/www/storage/tests/releases/R2018b"}',
           $actual
       );
    }

    public function testParseHandleString(): void
    {
        $actual = $this
            ->handlerClassString
            ->parseHandlerString(ProductUploadedSenderHandler::class . ':' . '{"aspectId":1, "productPath":"/var/www/storage/tests/releases/R2018b"}')
        ;

        $this->assertEqualsCanonicalizing(
            [ProductUploadedSenderHandler::class, ['aspectId' => 1, 'productPath' => $this->app->storagePath('/tests/releases/R2018b')]],
            $actual
        );
    }

    public function testMakeHandleString(): void
    {
        [$handler, $params] = $this
            ->handlerClassString
            ->parseHandlerString(ProductUploadedSenderHandler::class . ':' . '{"aspectId":1, "productPath":"/var/www/storage/tests/releases/R2018b"}')
        ;

        $handler = $this->app->make($handler, $params);
        /** @var SenderHandler $handler */
        $handler->data();
        $this->expectNotToPerformAssertions();
    }
}
