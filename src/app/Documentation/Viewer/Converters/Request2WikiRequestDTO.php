<?php

namespace App\Documentation\Viewer\Converters;

use App\Converter\Attributes\ExpectedType;
use App\Converter\Contracts\ConverterContract;
use App\Documentation\Viewer\DTO\WikiRequestDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final readonly class Request2WikiRequestDTO implements ConverterContract
{
    /**
     * @param Request $request
     * @return WikiRequestDTO
     */
    public function convert(#[ExpectedType(Request::class)] object $request): WikiRequestDTO
    {
        $lang = $request->lang;
        $space = $request->space ?? 'default';
        $page = $request->page;

        $extension = '';
        if (! empty($page) && Str::contains($page, '.')) {
            $extension = Str::afterLast($page, '.');
        }

        if (! empty($extension)) {
            $page = Str::replaceLast('.' . $extension, '', $page);
        }

        return new WikiRequestDTO(
            lang: $lang,
            product: 'wiki',
            space: $space,
            page: $page,
            extension: $extension,
        );
    }
}
