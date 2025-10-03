<?php

namespace App\Documentation\Uploader\Converters;

use App\Documentation\Uploader\DTO\UploadedFileDTO;
use Illuminate\Http\UploadedFile;
use SplFileInfo;

interface UploadedFileDTOConverterInterface
{
    public function buildByUploadedFile(UploadedFile $file): UploadedFileDTO;
    public function buildBySplFile(SplFileInfo $file): UploadedFileDTO;
    public function buildByPath(string $path): UploadedFileDTO;
}
