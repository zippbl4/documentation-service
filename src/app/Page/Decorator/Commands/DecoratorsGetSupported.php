<?php

namespace App\Page\Decorator\Commands;

use App\Page\Decorator\Contracts\SupportedDecoratorsInterface;
use Illuminate\Console\Command;

class DecoratorsGetSupported extends Command
{
    protected $signature = 'decorators:get-supported';

    protected $description = 'Декораторы в контейнере';

    public function handle(SupportedDecoratorsInterface $decorators): void {
        $supported = $decorators->getSupportedDecorators();

        $this->info('Count: ' . count($supported));
        foreach ($supported as $decorator) {
            $this->info($decorator);
        }
    }
}
