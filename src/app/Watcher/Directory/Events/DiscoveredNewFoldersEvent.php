<?php

namespace App\Watcher\Directory\Events;

class DiscoveredNewFoldersEvent
{
    public function __construct(
        public array $folders,
    ) {
        //
    }
}
