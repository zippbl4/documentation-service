<?php

namespace App\Symlink\Contracts;

interface SymlinkInterface
{
    public function link(string $source, string $target): void;
    public function unlink(string $link): void;
    public function read(string $link): ?string;
    public function has(string $link): bool;
}
