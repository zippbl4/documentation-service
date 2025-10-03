<?php

namespace App\Page\Driver\Drivers;

use App\Contracts\NameInterface;
use App\Page\Driver\Contracts\Driver;
use App\Page\Driver\DTO\DriverRequestDTO;
use App\Page\Driver\DTO\DriverResponseDTO;
use App\Page\Driver\Exceptions\DriverException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

final readonly class RemoteDriver implements NameInterface, Driver
{
    public function __construct(
        private Client $client,
    ) {
    }

    public static function getName(): string
    {
        return 'remote';
    }

    public function showPage(DriverRequestDTO $request): DriverResponseDTO
    {
        try {
            $response = $this->client->get($request->getRootWithPath());
        } catch (ClientException|GuzzleException $e) {
            $response = $e->getResponse();

            throw new DriverException(
                clientMessage: $response->getBody()->getContents(),
                message: $e->getMessage(),
                previous: $e
            );
        }

        return new DriverResponseDTO(
            title: '',
            content: $response->getBody()->getContents(),
        );
    }
}
