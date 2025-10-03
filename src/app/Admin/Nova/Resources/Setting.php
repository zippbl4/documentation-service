<?php

namespace App\Admin\Nova\Resources;

use App\Admin\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Setting extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Config\Entities\Settings>
     */
    public static string $model = \App\Config\Entities\Settings::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    public static $group = 'Настройки';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function label(): string
    {
        return 'Настройки';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        try {
            $config = sprintf(
                'app-config.%s: %s',
                $this->name,
                config('app-config.' . $this->name),
            );
        } catch (\Throwable $e) {
            // Честно говоря, это безумие...
            // Уже какое по счету???
            // ErrorException: Array to string conversion
            $config = sprintf(
                'app-config.%s: %s',
                $this->name,
                json_encode(config('app-config.' . $this->name)),
            );
        }

        return [
            ID::make()->sortable(),
            Text::make('Настройка', 'name'),
            Text::make('Значение', 'val')->onlyOnIndex(),
            Text::make('Текущее значение', function () use ($config) {
                return $config;
            }),
            Text::make('Описание', 'description')->onlyOnIndex(),
            Textarea::make('Описание', 'description')
                ->rows(5)
                ->alwaysShow()
            ,
            Textarea::make('Значение', 'val')
                ->rows(5)
                ->alwaysShow()
            ,
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
