<?php

namespace App\Page\Driver\Drivers;

use App\Contracts\NameInterface;
use App\Page\Driver\Contracts\Driver;
use App\Page\Driver\DTO\DriverRequestDTO;
use App\Page\Driver\DTO\DriverResponseDTO;
use App\Page\Driver\Exceptions\DriverException;
use Illuminate\Support\Str;
use SplFileObject;

final readonly class LocalDriver implements NameInterface, Driver
{
    public static function getName(): string
    {
        return 'local';
    }

    public function showPage(DriverRequestDTO $request): DriverResponseDTO
    {
        $path = Str::replace('//', '/', $request->getRootWithPath());

        try {
            $file = new SplFileObject($path);
        } catch (\RuntimeException $e) {
            throw new DriverException(
                clientMessage: 'Page cannot be opened',
                previous: $e,
            );
        } catch (\LogicException $e) {
            throw new DriverException(
                clientMessage: 'Page is a directory',
                previous: $e
            );
        }

        if (! $file->isFile()) {
            throw new DriverException(
                clientMessage: 'Page is not a file',
            );
        }

        return new DriverResponseDTO(
            title: $file->getFilename(),
            content: $file->fread($file->getSize()),
        );
    }
}
