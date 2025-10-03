<?php

namespace App\Documentation\AspectPlugin\PageDriver\Drivers;

use App\Contracts\NameInterface;
use App\Documentation\AspectPlugin\PageDriver\Repositories\PageRepositoryInterface;
use App\Page\Driver\Contracts\Driver;
use App\Page\Driver\DTO\DriverRequestDTO;
use App\Page\Driver\DTO\DriverResponseDTO;
use App\Page\Driver\Exceptions\DriverException;
use Illuminate\Validation\Factory as Validator;

final readonly class EloquentDriver implements NameInterface, Driver
{
    public function __construct(
        private PageRepositoryInterface $repository,
        private Validator $validator,
    ) {
    }

    public static function getName(): string
    {
        return 'eloquent';
    }

    public function createPage(array $uniquenessCondition, array $data): DriverResponseDTO
    {
        //->create([
        //                'aspect_id' => $aspect->entityId,
        //                'parent_id' => $parent?->id,
        //
        //                'lang' => $lang,
        //                'product' => 'wiki',
        //                'version' => 'default',
        //                'part' => Str::slug($request->title),
        //                'page'=>  $page,
        //
        //                'title' => $request->title,
        //                'content' => $request->input('content'),
        //            ])

        $validator = $this->validator->make($data, [
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            throw new DriverException(
                clientMessage: $validator->messages()->toJson(),
                message: sprintf(
                    'message: %s:%s, repository: %s, uniquenessCondition: %s, data: %s;',
                    'validator',
                    $validator->messages()->toJson(),
                    $this->repository::class,
                    json_encode($uniquenessCondition),
                    json_encode($data),
                )
            );
        }

        $page = $this->repository->create($uniquenessCondition, $data);

        return new DriverResponseDTO(
            title: $page->title,
            content: $page->content,
        );
    }

    public function showPage(DriverRequestDTO $request): DriverResponseDTO
    {
        $page = $this->findPage($request);

        return new DriverResponseDTO(
            title: $page->title,
            content: $page->content,
        );
    }

    public function updatePage(DriverRequestDTO $request, array $data): DriverResponseDTO
    {
        $page = $this->findPage($request);
        $page->update($data);

        return new DriverResponseDTO(
            title: $page->title,
            content: $page->content,
        );
    }

    private function findPage(DriverRequestDTO $request)
    {
        $page = $this->repository->findByFilter(
            $request->getFilters()
        );

        if ($page !== null) {
            return $page;
        }

        throw new DriverException(
            clientMessage: 'Page not found',
            message: sprintf(
                'message: %s, repository: %s, request: %s',
                'page is null',
                $this->repository::class,
                json_encode($request->getFilters()),
            )
        );
    }
}
