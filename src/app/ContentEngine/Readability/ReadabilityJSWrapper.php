<?php

namespace App\ContentEngine\Readability;

use App\ContentEngine\Contracts\ReadabilityInterface;
use App\ContentEngine\DTO\ReadabilityDTO;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\RequestOptions;
use Illuminate\Contracts\Config\Repository as Config;

class ReadabilityJSWrapper implements ReadabilityInterface
{
    public function __construct(
        private Config    $config,
        private Guzzle    $guzzle,
    ) {
    }

    public function parse(string $html): ReadabilityDTO
    {
        $start = microtime(true);

        $response = $this->guzzle->request(
            'POST',
            $this->config->get('services.readability.domain'),
            [
                RequestOptions::BODY => $html
            ],
        );

        $content = $response->getBody()->getContents();
        $output = json_decode($content, flags: JSON_THROW_ON_ERROR);

        $elapsed = microtime(true) - $start;

        return new ReadabilityDTO(
            title: $output->title,
            content: $output->content,
        );
    }
}
