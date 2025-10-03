<?php

namespace App\Documentation\AspectPlugin\Product;

use App\Archive\Researcher\DTO\ProductDTO;
use App\Documentation\Aspect\DTO\AspectIdDTO;
use App\Documentation\Researcher\Contracts\Handler;
use App\Documentation\Researcher\DTO\FileDTO;
use Psr\Log\LoggerInterface;

/**
 * @deprecated
 */
final class ProductSaverHandler implements Handler
{
    private array $products = [];

    public function __construct(
        private LoggerInterface   $logger,
        private ProductRepository $productRepository,
    ) {
    }

    public function handle(FileDTO $file): void
    {
        // TODO в текущей реализации сюда кроме ру языка больше ничего не попадет
        $this->products[(string) $file->toAspectId()] = $file->toAspectId();
    }

    public function flush(): void
    {
        // TODO так же тут есть нехватка параметров
        $this->productRepository->saveMany(
            ...array_map(fn(AspectIdDTO $item) => new ProductDTO(
                lang: $item->lang,
                product: $item->product,
                version: $item->version,
//                archiveHash: $job->archiveHash,
//                jobUuid: $job->uuid,
//                rootFolder: $archiveRootFolder,
//                rootPath: $aspect->path->getRoot(),
            ), $this->products)
        );

        $this->logger->debug("ProductSaverHandler: product info.");
    }
}
