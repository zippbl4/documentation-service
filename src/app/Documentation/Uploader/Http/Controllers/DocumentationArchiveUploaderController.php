<?php

namespace App\Documentation\Uploader\Http\Controllers;

use App\Archive\Unpacker\Contracts\UnpackerSupportedStrategiesInterface;
use App\Config\DTO\ConfigDTO;
use App\Documentation\Aspect\Contracts\AspectConverterInterface;
use App\Documentation\Aspect\Contracts\AspectRepositoryContract;
use App\Documentation\Aspect\Entities\Aspect;
use App\Documentation\Uploader\Converters\UploadedFileDTOConverterInterface;
use App\Documentation\Uploader\Http\Requests\StoreRequest;
use App\Documentation\Uploader\Managers\ArchiveUploaderJobManagerInterface;
use App\Documentation\Uploader\Services\ArchiveUploaderInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;

final class DocumentationArchiveUploaderController extends Controller
{
    public function __construct(
        private readonly AspectRepositoryContract             $repository,
        private readonly AspectConverterInterface             $converter,
        private readonly UnpackerSupportedStrategiesInterface $strategies,
        private readonly ArchiveUploaderInterface             $archiveService,
        private readonly UploadedFileDTOConverterInterface    $uploadedFileDTOConverter,
        private readonly ArchiveUploaderJobManagerInterface   $uploaderJobManager,
        private readonly ConfigDTO                            $config,
    ) {
    }

    public function store(StoreRequest $request): Response
    {
        $releaseArchive = $request->file(StoreRequest::RELEASE_ARCHIVE_FIELD);
        $unpackStrategy = $request->get(StoreRequest::UNPACK_STRATEGY_FIELD);
        $aspectId = $request->get(StoreRequest::ASPECT_ID_FIELD);

        $releaseArchive = $this->archiveService->moveReleaseArchive(
            $this->uploadedFileDTOConverter->buildByUploadedFile($releaseArchive),
            $this->config->zipFolder,
        );

        $releaseArchiveHash = md5_file($releaseArchive->getPathname());

        $this->uploaderJobManager->createJob(
            $aspectId,
            $releaseArchiveHash,
            $unpackStrategy,
            $releaseArchive->getPathname(),
        );

        return new Response(status: Response::HTTP_CREATED);
    }

    public function index(): JsonResponse
    {
        $aspects = $this
            ->repository
            ->findAll()
            ->mapWithKeys(fn (Aspect $aspect) => [
                $aspect->id => sprintf(
                    '%s (%s)',
                    $aspect->name,
                    $this->converter->convertToAspectId($aspect)),
            ])
            ->toArray()
        ;

        $strategies = $this->strategies->getSupportedStrategies();
        $strategies = array_combine($strategies, $strategies);

        return new JsonResponse([
            'title' => 'Загрузить архив',
            'fields' => [
                Select::make('Спецификация', StoreRequest::ASPECT_ID_FIELD)->options($aspects),
                Select::make('Стратегия распаковки', StoreRequest::UNPACK_STRATEGY_FIELD)->options($strategies),
                File::make('Архив', StoreRequest::RELEASE_ARCHIVE_FIELD),
            ],
        ]);
    }
}
