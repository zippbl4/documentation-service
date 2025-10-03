<?php

namespace App\Watcher\Directory\Contracts;

interface DirectoryWatcherJobManagerInterface
{
    public function createJob(): void;
}
