<?php

namespace App\AsyncTask\Channels;

use Tests\TestCase;

class RedisPubSubChannelTest extends TestCase
{
    public function testWRITE()
    {
        $channel = new RedisPubSubChannel();
        $channel->open('test1');
        $channel->send('test1');
        $channel->send('test2');
        $channel->send('test3');
    }

    public function testREAD()
    {
        $channel = new RedisPubSubChannel();
        $channel->open('test1');
        $data = $channel->receiveAll();

        foreach ($data as $item) {
            dump($item);
        }

    }
}
