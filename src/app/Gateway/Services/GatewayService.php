<?php

namespace App\Gateway\Services;

use App\Gateway\Contracts\GatewayAuthenticatableInterface;
use App\Gateway\Contracts\GatewayInterface;
use App\Gateway\Contracts\HttpClientInterface;
use App\Gateway\Contracts\RequestInterface;
use App\Gateway\DTO\Auth\LoginResponseObject;
use App\Gateway\DTO\Vacancies\GetNewVacanciesResponseObject;
use App\Gateway\DTO\Vacancies\GetRecommendationVacanciesResponseObject;
use App\Gateway\Endpoints\GetNewVacanciesRequestObject;
use App\Gateway\Endpoints\GetRecommendationVacanciesRequestObject;
use App\Gateway\Endpoints\LoginRequestObject;
use App\ObjectMapper\Contracts\JsonDeserializerInterface;

final readonly class GatewayService implements GatewayAuthenticatableInterface, GatewayInterface
{
    public function __construct(
        private HttpClientInterface       $client,
        private JsonDeserializerInterface $deserializer,
    ) {
    }

    public function endpoint(RequestInterface $request, string $resultDto): object
    {
        return $this->deserializer->deserialize(
            $this->client->handle($request)->getResponse(),
            $resultDto
        );
    }

    public function login(string $email, string $password): LoginResponseObject
    {
        return $this->endpoint(
            new LoginRequestObject(
                email: $email,
                password: $password,
            ),
            LoginResponseObject::class
        );
    }

    public function getNewVacancies(string $uuid): GetNewVacanciesResponseObject
    {
        return $this->endpoint(
            new GetNewVacanciesRequestObject(
                uuid: $uuid,
            ),
            GetNewVacanciesResponseObject::class
        );
    }

    public function getRecommendationVacancies(
        array $spheres = [],
        array $tags = []
    ): GetRecommendationVacanciesResponseObject {
        return $this->endpoint(
            new GetRecommendationVacanciesRequestObject(
                limit: 3,
                spheres: $spheres,
                tags: $tags
            ),
            GetRecommendationVacanciesResponseObject::class
        );
    }
}
