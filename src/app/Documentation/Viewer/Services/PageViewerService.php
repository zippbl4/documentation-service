<?php

namespace App\Documentation\Viewer\Services;

use App\Context\Context;
use App\Converter\Contracts\ConverterServiceContract;
use App\Dictionary\ContextDictionary;
use App\Documentation\Aspect\Contracts\AspectServiceContract;
use App\Documentation\Viewer\DTO\PageRequestDTO;
use App\Documentation\Viewer\DTO\PageResponseDTO;
use App\Page\Decorator\Contracts\ContentDecorationInterface;
use App\Page\Driver\Contracts\DriverFactoryInterface;
use App\Page\Driver\DTO\DriverRequestDTO;
use ArrayObject;

final readonly class PageViewerService implements PageViewerInterface
{
    public function __construct(
        private AspectServiceContract                $aspectService,
        private ConverterServiceContract             $converter,
        private DriverFactoryInterface               $driverFactory,
        private ContentDecorationInterface           $contentDecoration,
    ) {
    }

    public function showPage(PageRequestDTO $request): PageResponseDTO
    {
        $aspect = $this->aspectService->getAspectByAspectId($request->getAspectId());

        $path = $aspect
            ->getPathBuilder()
            ->useFullPath()
            ->setLang($request->lang)
            ->setProduct($request->product)
            ->setVersion($request->version)
            ->setPage($request->page)
            ->setExtension($request->extension)
            ->buildObject()
        ;

        $driverResponse = $this
            ->driverFactory
            ->getDriver($aspect->getDriver())
            ->showPage($this->converter->convert($path, DriverRequestDTO::class))
        ;

        $decorators = (array) $this->converter->convert($aspect->decorator, ArrayObject::class);

        $ctx = (new Context())
            ->withValue(ContextDictionary::PAGE_REQUEST_DTO, $request)
            ->withValue(ContextDictionary::ASPECT_DTO, $aspect)
            ->withValue(ContextDictionary::BUILT_PATH_DTO, $path)
        ;

        $decoratedContent = $this->contentDecoration->decorate(
            $driverResponse->getContent(),
            $decorators,
            $ctx,
        );

        return new PageResponseDTO(
            title: $driverResponse->getTitle(),
            content: $decoratedContent,
            context: $ctx,
        );
    }
}
