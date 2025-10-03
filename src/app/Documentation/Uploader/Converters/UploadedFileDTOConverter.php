<?php

namespace App\Documentation\Uploader\Converters;

use App\Documentation\Uploader\DTO\UploadedFileDTO;
use Illuminate\Http\UploadedFile;
use SplFileInfo;

final readonly class UploadedFileDTOConverter implements UploadedFileDTOConverterInterface
{
    public function buildByUploadedFile(UploadedFile $file): UploadedFileDTO
    {
        return new UploadedFileDTO(
            clientName: $file->getClientOriginalName(),
            clientExtension: $file->getClientOriginalExtension(),
            file: $file->getFileInfo(),
        );
    }

    public function buildBySplFile(SplFileInfo $file): UploadedFileDTO
    {
        return new UploadedFileDTO(
            clientName: $file->getBasename(),
            clientExtension: $file->getExtension(),
            file: $file,
        );
    }

    public function buildByPath(string $path): UploadedFileDTO
    {
        return $this->buildBySplFile(new SplFileInfo($path));
    }
}
