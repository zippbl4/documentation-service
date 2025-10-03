<?php

namespace App\Http\Requests\Api;

use App\Documentation\Correction\DTO\CorrectionDTO;
use App\Http\Requests\AbstractRequest;

class CorrectionStoreRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'releaseName' => ['required', 'string'],
            'pageUrl' => ['required', 'string'],
            'pageXpath' => ['required', 'string'],
            'contentEng' => ['required', 'string'],
            'contentRusOld' => ['required', 'string'],
            'contentRusNew' => ['required', 'string'],
        ];
    }

    public function getDTO(): CorrectionDTO
    {
        return $this->deserializer()->deserialize(
            $this->getContent(),
            CorrectionDTO::class
        );
    }
}
