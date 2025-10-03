<?php

namespace App\Documentation\Searcher\Converters;

use App\Documentation\Searcher\DTO\SearchRequestDTO;
use Illuminate\Http\Request;

final readonly class RequestConverter
{
    public function convertToSearchRequestDTO(Request $request): SearchRequestDTO
    {
        return new SearchRequestDTO(
            query: $request->query('qdoc'),
        );
    }
}
