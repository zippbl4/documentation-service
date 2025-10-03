<?php

namespace App\Documentation\Uploader\DTO;

use SplFileInfo;

/**
 * @mixin SplFileInfo
 */
final readonly class UploadedFileDTO
{
    public function __construct(
        /** @example R2018r.zip */
        private string $clientName,
        /** @example zip */
        private string $clientExtension,
        private \SplFileInfo $file,
    ) {
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }

    public function getClientExtension(): string
    {
        return $this->clientExtension;
    }

    public function getName(): string
    {
        return trim($this->getClientName(), '.' . $this->getClientExtension());
    }

    public function getFile(): SplFileInfo
    {
        return $this->file;
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->file, $name], $arguments);
    }
}
