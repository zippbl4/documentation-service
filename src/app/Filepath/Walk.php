<?php

namespace App\Filepath;

use FilesystemIterator;
use SplFileInfo;

final readonly class Walk
{
    /**
     * @see https://pkg.go.dev/path/filepath#Walk
     * @param string $root
     * @param callable(string $path, SplFileInfo $info): void $fn
     * @return void
     */
    public function walk(string $root, callable $fn): void
    {
        $this->innerWalk($root, new SplFileInfo($root), $fn);
    }

    private function innerWalk(string $path, SplFileInfo $info, callable $fn): void
    {
        if (! $info->isDir()) {
            $fn($path, $info);
            return;
        }

        $files = $this->readDirNames($path);
        $fn($path, $info);

        foreach ($files as $file) {
            $filename = (string) $file;
            $this->innerWalk($filename, $file, $fn);
        }
    }

    private function readDirNames(string $path): FilesystemIterator
    {
        return new FilesystemIterator($path);
    }
}
