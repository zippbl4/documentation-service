<?php

namespace Test\Unit\Archive\Uploader;

use App\Documentation\Uploader\Events\ProductUploaded;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Tests\TestCase;
use Tests\Unit\Archive\Uploader\DummyListener;

class Test extends TestCase
{
    public function test1()
    {
        $dispatcher = $this->app->make(Dispatcher::class);
//        $this->app->afterResolving(DummyListener::class, function (DummyListener $listener) {
//            $listener->setHandler('test');
//        });

        $dispatcher->listen(ProductUploaded::class, function (ProductUploaded $event) {});
        $dispatcher->listen(ProductUploaded::class, DummyListener::class);
        $dispatcher->dispatch(ProductUploaded::class, new ProductUploaded(
            aspectId: -1,
            jobUuid: 'dummy',
            productPath: app()->storagePath('/tests/release/R2018b'),
        ));
    }
}
