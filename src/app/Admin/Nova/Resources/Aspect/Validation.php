<?php

namespace App\Admin\Nova\Resources\Aspect;

use App\Admin\Nova\Resource;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Validation extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Documentation\Aspect\Entities\Validation>
     */
    public static string $model = \App\Documentation\Aspect\Entities\Validation::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'page';

    public static $group = 'Аспекты';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    public static function label(): string
    {
        return 'Валидаторы архива';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(),
            BelongsTo::make('Спецификация', 'aspect', Aspect::class),
            Text::make('page'),
        ];
    }
}
