<?php

namespace App\Documentation\Searcher\Listeners;

use App\Config\DTO\ConfigDTO;
use App\Config\Enums\FeatureFlagEnum;
use App\Documentation\Researcher\Contracts\ResearcherServiceInterface;
use App\Documentation\Uploader\Events\ProductUploaded;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * @deprecated
 */
class OnProductUploadedRunIndexerJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        private LoggerInterface            $logger,
        private ConfigDTO                  $config,
        private ResearcherServiceInterface $researcherService,
        private IndexerHandler             $handler,
    )
    {
    }

    public function handle(ProductUploaded $event): void
    {
        if ($this->config->productIndexerFeatureFlag === FeatureFlagEnum::Disabled) {
            $this->logger->info('OnProductUploadedRunIndexerJob feature disabled.');

            return;
        }

        $this->researcherService->handlePages(
            $this->handler,
            $event,
        );
    }

    public function failed(ProductUploaded $event, Throwable $e): void
    {

    }
}
