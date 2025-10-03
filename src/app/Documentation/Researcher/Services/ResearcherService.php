<?php

namespace App\Documentation\Researcher\Services;

use App\Config\DTO\ConfigDTO;
use App\Documentation\Aspect\Contracts\AspectServiceContract;
use App\Documentation\Researcher\Contracts\Handler;
use App\Documentation\Researcher\Contracts\ResearcherServiceInterface;
use App\Documentation\Researcher\DTO\FileDTO;
use App\Documentation\Uploader\Events\ProductUploaded;
use Psr\Log\LoggerInterface;
use Symfony\Component\Finder\Finder;

/**
 * @deprecated
 */
final readonly class ResearcherService implements ResearcherServiceInterface
{
    public function __construct(
        private LoggerInterface       $logger,
        private ConfigDTO             $config,
        private AspectServiceContract $aspectService,
    ) {
    }

    public function handlePages(Handler $handler, ProductUploaded $event): void
    {
        $aspect = $this->aspectService->getAspect($event->aspectId);
        $aspectConfig = $aspect->getConfig();
        $pathPatternRegex = $aspect->getPathPatternRegexFilledByAspect();
        $path = $event->productPath;

        $this->logger->debug("ResearcherService: starting.", $event->toArray());

        if ($path === $this->config->releaseFolder) {
            $this->logger->debug("ResearcherService: 'productPath' is 'releaseFolder'.", $event->toArray());

            return;
        }

        if ($aspectConfig->finderProductPageExtension === null || $aspectConfig->finderProductLang === null) {
            $this->logger->debug("ResearcherService: aspect config error.", $event->toArray() + $aspectConfig->toArray());

            return;
        }

        $finder = (new Finder())
            ->in($path)
            ->name($aspectConfig->finderProductPageExtension)
            ->files()
        ;

        if (! $finder->hasResults()) {
            $this->logger->debug("ResearcherService: 'productPath' is empty.", $event->toArray() + $aspectConfig->toArray());

            return;
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

            if ($lang !== $aspectConfig->finderProductLang) {
                continue;
            }

            $handler->handle(new FileDTO(
                lang: $lang,
                product: $product,
                version: $version,
                page: $page,
                content: $file->getContents(),
            ));
        }

        if (method_exists($handler, 'flush')) {
            $handler->flush();
        }

        $this->logger->debug("ResearcherService: end.", $event->toArray());
    }
}
