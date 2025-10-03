<?php

namespace App\Documentation\Researcher\Contracts;

use App\Documentation\Researcher\DTO\FileDTO;

/**
 * @deprecated
 */
interface Handler
{
    public function handle(FileDTO $file): void;
}
