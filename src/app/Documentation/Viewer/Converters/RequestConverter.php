<?php

namespace App\Documentation\Viewer\Converters;

use App\Documentation\Viewer\Contracts\RequestConverterContract;
use App\Documentation\Viewer\DTO\PageRequestDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @deprecated
 */
final readonly class RequestConverter implements RequestConverterContract
{
    public function convertToPageRequestDTO(Request $request): PageRequestDTO
    {
        $lang = $request->lang;
        if ($lang === null) {
            $lang = 'rus';
        }

        $product = $request->product;
        if ($product === null) {
            throw new \InvalidArgumentException('Aspect can\'t be empty!');
        }

        $version = $request->version;

        if ($version === null && $product === 'wiki') {
            $version = $product;
        }

        $page = $request->page ?? '';
//        if ($page === null) {
//            throw new \InvalidArgumentException('Page can\'t be empty!');
//        }

        $extension = '';
        if (! empty($page) && Str::contains($page, '.')) {
            $extension = Str::afterLast($page, '.');
        }

        if (! empty($extension)) {
            $page = Str::replaceLast('.' . $extension, '', $page);
        }


        $page = strtok($page, "?");
        $page = strtok($page, "#");

        return new PageRequestDTO(
            lang: $lang,
            product: $product,
            version: $version,
            page: $page,
            extension: $extension,
        );
    }
}
