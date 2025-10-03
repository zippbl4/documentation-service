<?php

namespace App\ContentEngine\Diff;

use App\ContentEngine\Contracts\DiffInterface;
use FineDiff\Diff;

class DiffFineDiffWrapper implements DiffInterface
{
    public function diff(string $from, string $to): string
    {
        return (new Diff())->render($from, $to);
    }
}
