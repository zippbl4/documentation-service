<?php

namespace App\Gateway\Services;

use App\Gateway\Contracts\HttpClientInterface;
use App\Gateway\Contracts\RequestBodyInterface;
use App\Gateway\Contracts\RequestHeadersInterface;
use App\Gateway\Contracts\RequestInterface;
use App\Gateway\Contracts\RequestQueryInterface;
use App\Gateway\Contracts\ResponseInterface;
use App\Gateway\DTO\Response;
use App\Gateway\Exceptions\HttpClientException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Contracts\Config\Repository as Config;
use Psr\Log\LoggerInterface;

final readonly class HttpClient implements HttpClientInterface
{
    public function __construct(
        private Client          $client,
        private Config          $config,
        private LoggerInterface $logger,
    ) {
    }

    /** @inheritDoc */
    public function handle(RequestInterface $request): ResponseInterface
    {
        $domain = $this->config->get('services.gw.domain');

        $options = [];
        if ($request instanceof RequestQueryInterface) {
            $options[RequestOptions::QUERY] = $request->getQueryParameters();
        }

        if ($request instanceof RequestBodyInterface) {
            $options[RequestOptions::JSON] = $request->getRequestBody();
        }

        if ($request instanceof RequestHeadersInterface) {
            $options[RequestOptions::HEADERS] = $request->getHeaders();
        }

        $this->logger->info('Request: ' . $request);

        try {
            $response = $this->client->request(
                $request->getMethod(),
                $domain . '/' . $request->getUrl(),
                $options,
            );
        } catch (ClientException $e) {
            $this->logger->error('Error[4xx]: ' . $e);

            $response = $e->getResponse();
        } catch (GuzzleException $e) {
            $this->logger->error('Error[5xx]: ' . $e);

            throw new HttpClientException($e->getMessage(), '', $e);
        }

        $content = $response->getBody()->getContents();

        $this->logger->info('Response: ' . $content);

        try {
            json_decode($content, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->logger->error('Error[json]: ' . $e);

            throw new HttpClientException($e->getMessage(), $content, $e);
        }

        return new Response(
            statusCode: $response->getStatusCode(),
            response: $content
        );
    }
}
