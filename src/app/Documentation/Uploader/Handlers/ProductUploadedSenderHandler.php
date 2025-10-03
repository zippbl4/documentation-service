<?php

namespace App\Documentation\Uploader\Handlers;

use App\AsyncTask\Contracts\Handlers\SenderHandler;
use App\Config\DTO\ConfigDTO;
use App\Documentation\Aspect\Contracts\AspectServiceContract;
use App\Documentation\Aspect\DTO\AspectDTO;
use App\Documentation\Researcher\DTO\FileDTO;
use App\ObjectMapper\Contracts\JsonDeserializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Finder\Finder;

class ProductUploadedSenderHandler implements SenderHandler
{
    private AspectDTO $aspect;

    public function __construct(
        private JsonDeserializerInterface $deserializer,
        private LoggerInterface           $logger,
        private AspectServiceContract     $aspectService,
        private ConfigDTO                 $config,

        private int                       $aspectId,
        private string                    $productPath,
    ) {
        $this->aspect = $this->aspectService->getAspect($this->aspectId);
    }

    public function data(): ?iterable
    {
        $aspectConfig = $this->aspect->getConfig();
        $pathPatternRegex = $this->aspect->getPathPatternRegexFilledByAspect();
        $path = $this->productPath;
        $releaseFolder = $this->config->releaseFolder;

        $this->logger->debug("ProductUploadedSenderHandler: starting.");

        if ($path === $releaseFolder) {
            $this->logger->debug("ProductUploadedSenderHandler: 'productPath ($path)' is 'releaseFolder ($releaseFolder)'.");

            return null;
        }

        // TODO сделать невозможным запуск без этого конфига
        if ($aspectConfig->finderProductPageExtension === null) {
            $this->logger->debug("ProductUploadedSenderHandler: aspect config error.", $aspectConfig->toArray());

            return null;
        }

        $finder = (new Finder())
            ->in($path)
            ->name($aspectConfig->finderProductPageExtension)
            ->files()
        ;

        if (! $finder->hasResults()) {
            $this->logger->debug("ProductUploadedSenderHandler: 'productPath ($path)' is empty.");

            return null;
        }

        foreach ($finder as $file) {
            preg_match($pathPatternRegex, $file->getPathname(), $matches);

            if (empty($matches['page'])) {
                continue;
            }

            [
                'version' => $version,
                'lang' => $lang,
                'product' => $product,
                'page' => $page,
            ] = $matches;

            yield $this->deserializer->serialize(new FileDTO(
                lang: $lang,
                product: $product,
                version: $version,
                page: $page,
                content: $file->getContents(),
            ));
        }

        $this->logger->debug("ProductUploadedSenderHandler: end.");
    }
}
