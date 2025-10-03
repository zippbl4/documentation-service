<?php

namespace App\AsyncTask\Channels;

use Tests\TestCase;

class FileBufferTest extends TestCase
{
    public function testWRITE()
    {
        $fileBuffer = new PipeBuffer();
        $fileBuffer->open('PipeBuffer.txt');
        $fileBuffer->write('test');
        sleep(1);
        $fileBuffer->write('test1');
        sleep(1);
        $fileBuffer->write('test2');
    }

    public function testREAD()
    {
        $fileBuffer = new PipeBuffer();
        $fileBuffer->open('PipeBuffer.txt');
        while ($data = $fileBuffer->read()) {
            echo $data;
        }
        $fileBuffer->close();
    }

    public function test1()
    {
        $path = storage_path('test');
        unlink($path);
        posix_mkfifo($path, 0666);
    }

    public function test2()
    {
        $path = storage_path('test');
        $fp = fopen($path, 'r');
        while ($data = fgets($fp)) {
            echo "Received: $data\n";
        }
        fclose($fp);
    }

    public function test3()
    {
        $path = storage_path('test');
        $fp = fopen($path, 'w');
        fwrite($fp, "Hello from FIFO!\n");
        fwrite($fp, "Hello from FIFO!\n");
        fwrite($fp, "Hello from FIFO!\n");
        fwrite($fp, "Hello from FIFO!\n");
        fwrite($fp, "Hello from FIFO!\n");
        fwrite($fp, "Hello from FIFO!\n");
        fwrite($fp, "Hello from FIFO!\n");
        fwrite($fp, "Hello from FIFO!\n");
        fwrite($fp, "Hello from FIFO!\n");
        fwrite($fp, "Hello from FIFO!\n");
        fwrite($fp, "Hello from FIFO!\n");
        fclose($fp);
    }
}
