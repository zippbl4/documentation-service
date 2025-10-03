<?php

namespace App\Symlink\Services;

use App\Symlink\Contracts\SymlinkInterface;

final class Symlink implements SymlinkInterface
{
    public function link(string $source, string $target): void
    {
        symlink($target, $source);
    }

    public function unlink(string $link): void
    {
        unlink($link);
    }

    public function read(string $link): ?string
    {
        $link = readlink($link);
        return $link === false ? null : $link;
    }

    public function has(string $link): bool
    {
        if (! file_exists($link)) {
            return false;
        }

        if (! is_link($link)) {
            return false;
        }

        return true;
    }


}
