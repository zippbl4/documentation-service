<?php

namespace App\Documentation\AspectPlugin\Product;

use App\Config\DTO\ConfigDTO;
use App\Documentation\Researcher\Contracts\ResearcherServiceInterface;
use App\Documentation\Uploader\Events\ProductUploaded;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Psr\Log\LoggerInterface;

/**
 * @deprecated
 */
class OnProductUploadedRunProductSaverJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        private LoggerInterface            $logger,
        private ConfigDTO                  $config,
        private ResearcherServiceInterface $researcherService,
        private ProductSaverHandler        $handler
    ) {
    }

    public function handle(ProductUploaded $event): void
    {
        $this->researcherService->handlePages(
            $this->handler,
            $event,
        );
    }
}
