<?php

namespace Tests\Unit\Page\Decorator\Decorators;

use App\Documentation\Viewer\Contracts\RequestConverterContract;
use App\Documentation\Viewer\DTO\PageRequestDTO;
use App\Documentation\Viewer\DTO\SearchRequestDTO;
use Illuminate\Http\Request;

final readonly class DummyRequestPageDTOBuilder implements RequestConverterContract
{

    public function buildPageByRequest(Request $request): PageRequestDTO
    {
        return new PageRequestDTO(
            lang: 'rus',
            product: 'matlab',
            version: 'R2018b',
            page: 'matlab/index.html',
        );
    }

    public function buildSearchByRequest(Request $request): SearchRequestDTO
    {
        return new SearchRequestDTO(
            query: 'rus',
        );
    }
}
