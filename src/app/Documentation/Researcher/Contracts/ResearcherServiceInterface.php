<?php

namespace App\Documentation\Researcher\Contracts;

use App\Documentation\Uploader\Events\ProductUploaded;

/**
 * @deprecated
 */
interface ResearcherServiceInterface
{
    public function handlePages(Handler $handler, ProductUploaded $event): void;
}
