<?php

namespace App\ContentEngine\Readability;

use App\ContentEngine\Contracts\ReadabilityInterface;
use App\ContentEngine\DTO\ReadabilityDTO;
use fivefilters\Readability\Configuration;
use fivefilters\Readability\Readability;

class ReadabilityPHPWrapper implements ReadabilityInterface
{
    public function parse(string $html): ReadabilityDTO
    {
        $readability = new Readability(new Configuration());
        $readability->parse($html);

        return new ReadabilityDTO(
            title: $readability->getTitle(),
            content: $readability->getContent(),
        );
    }
}
