<?php

namespace App\Documentation\Viewer\Contracts;

use App\Converter\Contracts\ConverterServiceContract;
use App\Documentation\Viewer\Converters\Request2PageRequestDTO;
use App\Documentation\Viewer\DTO\PageRequestDTO;
use Illuminate\Http\Request;

/**
 * Использовать в новом варианте так:
 *
 * <pre>
 * public function __construct(
 *      private ConverterServiceContract $converter,
 * ) {}
 *
 * public function foo(Request $request): void
 * {
 *      $this->converter->convert($request, PageRequestDTO::class)
 * }
 * </pre>
 * @deprecated
 * @use ConverterServiceContract
 * @use Request2PageRequestDTO
 *
 */
interface RequestConverterContract
{
    public function convertToPageRequestDTO(Request $request): PageRequestDTO;
}
