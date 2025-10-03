<?php

namespace App\Filepath;

use Tests\TestCase;

class WalkTest extends TestCase
{
    public function testWalk(): void
    {
        (new Walk())->walk(__DIR__, function (string $path, \SplFileInfo $info): void {
            dump($path);
        });
    }
}
