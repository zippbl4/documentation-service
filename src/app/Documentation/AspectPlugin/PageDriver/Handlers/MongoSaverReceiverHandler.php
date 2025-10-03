<?php

namespace App\Documentation\AspectPlugin\PageDriver\Handlers;

use App\AsyncTask\Contracts\Handlers\ReceiveHandler;
use App\ContentEngine\Contracts\ReadabilityInterface;
use App\Documentation\AspectPlugin\PageDriver\Entities\MongoPage;
use App\Documentation\Researcher\DTO\FileDTO;
use App\ObjectMapper\Contracts\JsonDeserializerInterface;
use Illuminate\Database\DatabaseManager;
use MongoDB\BSON\ObjectId;
use Psr\Log\LoggerInterface;

/**
 * @stateful
 */
final class MongoSaverReceiverHandler implements ReceiveHandler
{
    public function __construct(
        private JsonDeserializerInterface $deserializer,
        private LoggerInterface           $logger,
        private DatabaseManager           $databaseManager,
        private ReadabilityInterface      $readability,

        /**
         * @var int
         */
        private int $aspectId,
    ) {
    }

    public function receive(string $data): void
    {
        $file = $this->deserializer->deserialize($data, FileDTO::class);

        $this->logger->debug("MongoSaverReceiverHandler: receive file: $file->page");

        try {
            $readability = $this->readability->parse($file->content);
        } catch (\Throwable $e) {
            $this->logger->error("MongoSaverReceiverHandler: error: " . $e->getMessage(), [
                'file' => $file->page,
            ]);
        }

        $payload = array_merge($file->toArray(), [
            'title' => $readability?->title ?? $file->page,
            'content' => $readability?->content ?? $file->content,
        ]);

        [
            'lang' => $lang,
            'product' => $product,
            'version' => $version,
            'page' => $page,
        ] = $payload;

//        $this->databaseManager->connection('mongodb')->transaction(fn () => );

        $this->addNode(
            parent: null,
            parts: explode('/', $page),
            payload: $payload,
        );
    }

    private function addNode(
        ?MongoPage $parent = null,
        array      $parts,
        array      $payload,
    ): void {
        if (empty($parts)) {
            return;
        }

        [
            'lang' => $lang,
            'product' => $product,
            'version' => $version,
            'page' => $page,
            'title' => $title,
            'content' => $content,
        ] = $payload;

        $part = array_shift($parts);
        $item = MongoPage::updateOrCreate([
            'lang' => $lang,
            'product' => $product,
            'version' => $version,
            'part' => $part,
        ], [
            'aspect_id' => $this->aspectId,
            'parent_id' => $parent === null ? null : new ObjectId($parent->id),
            'page' => ! empty($parts) ? $part : $page,
            'title' => ! empty($parts) ? $part : $title,
            'content' => ! empty($parts) ? $part : $content,
        ]);

        $this->addNode($item, $parts, $payload);
    }
}
