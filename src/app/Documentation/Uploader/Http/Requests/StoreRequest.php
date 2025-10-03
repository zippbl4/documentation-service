<?php

namespace App\Documentation\Uploader\Http\Requests;

use App\Archive\Unpacker\Contracts\UnpackerSupportedStrategiesInterface;
use App\Documentation\Aspect\Entities\Aspect;
use App\Documentation\Uploader\Http\Rules\CheckRequiredArchiveFiles;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

/**
 * @property int $aspectId
 * @property string $unpackStrategy
 * @property file $releaseArchive
 */
class StoreRequest extends FormRequest
{
    public const ASPECT_ID_FIELD = 'aspectId';
    public const UNPACK_STRATEGY_FIELD = 'unpackStrategy';
    public const RELEASE_ARCHIVE_FIELD = 'releaseArchive';

    public function rules(Container $app): array
    {
        return [
            self::ASPECT_ID_FIELD => [
                'required',
                'integer',
                Rule::exists(Aspect::class, 'id'),
            ],
            self::RELEASE_ARCHIVE_FIELD => [
                'required',
                'file',
                $app->make(CheckRequiredArchiveFiles::class, ['aspectId' => $this->aspectId]),
            ],
            self::UNPACK_STRATEGY_FIELD => [
                'required',
                new In($app->make(UnpackerSupportedStrategiesInterface::class)->getSupportedStrategies()),
            ],
        ];
    }

    /**
     * Get the URL to redirect to on a validation error.
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->redirector->getUrlGenerator()->current();
    }
}
