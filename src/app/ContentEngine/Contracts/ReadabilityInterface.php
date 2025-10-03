<?php

namespace App\ContentEngine\Contracts;

use App\ContentEngine\DTO\ReadabilityDTO;

interface ReadabilityInterface
{
    /**
     * @throws \fivefilters\Readability\ParseException
     * @throws \JsonException
     */
    public function parse(string $html): ReadabilityDTO;
}
