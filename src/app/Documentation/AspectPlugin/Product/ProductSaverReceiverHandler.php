<?php

namespace App\Documentation\AspectPlugin\Product;

use App\AsyncTask\Contracts\Handlers\ReceiveHandler;
use App\Documentation\Aspect\DTO\AspectIdDTO;
use App\Documentation\Researcher\DTO\FileDTO;
use App\ObjectMapper\Contracts\JsonDeserializerInterface;
use Psr\Log\LoggerInterface;

final class ProductSaverReceiverHandler implements ReceiveHandler
{
    private array $products = [];

    public function __construct(
        private JsonDeserializerInterface $deserializer,
        private LoggerInterface           $logger,
        private ProductRepository         $productRepository,

        private int $aspectId,
        private string $productPath,
    ) {
    }

    public function receive(string $data): void
    {
        $file = $this->deserializer->deserialize($data, FileDTO::class);
        $this->products[(string) $file->toAspectId()] = $file->toAspectId();
    }

    public function flush(): void
    {
        $this->productRepository->saveMany(
            ...array_map(fn(AspectIdDTO $item) => new ProductDTO(
                aspect_id: $this->aspectId,
                lang: $item->lang,
                product: $item->product,
                version: $item->version,
                archiveHash: '',
                jobUuid: '',
                rootFolder: $this->productPath,
                rootPath: '',
            ), $this->products)
        );

        $this->logger->debug("ProductSaverReceiverHandler: product info.");
    }
}
