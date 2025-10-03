<?php

namespace App\Aspect\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Archive\Uploader\Http\Requests\StoreRequest;
use Illuminate\Http\JsonResponse;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Resources\CreateViewResource;
use Laravel\Nova\Panel;

final class FieldsController extends Controller
{
    public function index(NovaRequest $request): JsonResponse
    {
        $t = [
            Select::make('Спецификация', StoreRequest::ASPECT_ID_FIELD)->options([]),
            Select::make('Стратегия распаковки', StoreRequest::UNPACK_STRATEGY_FIELD)->options([]),
            File::make('Архив', StoreRequest::RELEASE_ARCHIVE_FIELD)
        ];

        return new JsonResponse([
            'title' => 'Загрузить архив',
            'fields' => $t,
            'panels' => [
                Panel::make('TEST', $t),
            ],
        ]);
    }
}
