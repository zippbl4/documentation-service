<?php

namespace App\ContentEngine\Contracts;

use FineDiff\Exceptions\GranularityCountException;

interface DiffInterface
{
    /**
     * @throws GranularityCountException
     */
    public function diff(string $from, string $to): string;
}
