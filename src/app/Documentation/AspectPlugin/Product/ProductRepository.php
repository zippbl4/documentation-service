<?php

namespace App\Documentation\AspectPlugin\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class ProductRepository
{
    public function saveMany(ProductDTO ...$products): void
    {
        foreach ($products as $product) {
            $this->getBuilder()->firstOrCreate([
                'aspect_id' => $product->aspect_id,
                'lang' => $product->lang,
                'product' => $product->product,
                'version' => $product->version,
            ], [
                'archive_hash' => $product->archiveHash,
                'job_uuid' => $product->jobUuid,
                'root_folder' => $product->rootFolder,
                'root_path' => $product->rootPath,
            ]);
        }
    }

    private function getBuilder(): Builder
    {
        return (new ProductEntity())->newQuery();
    }
}
