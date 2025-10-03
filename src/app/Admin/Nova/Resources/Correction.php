<?php

namespace App\Admin\Nova\Resources;

use App\Admin\Nova\Resource;
use App\Correction\StatisticCard;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Correction extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Documentation\Correction\Entities\Correction>
     */
    public static string $model = \App\Documentation\Correction\Entities\Correction::class;

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
        return 'Правки';
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
            new Panel('Информация', [
                ID::make()->sortable(),
                Text::make('Релиз', 'release_name')
                    ->readonly(),
                BelongsTo::make('Пользователь', 'user', User::class)
                    ->readonly(),
                Boolean::make('Подтверждено', 'is_approved'),
                Boolean::make('Слито', 'is_merged'),
                Boolean::make('Архивировано', 'is_archived'),
            ]),

            new Panel('Детальная информация', [
                URL::make('Ссылка', fn () => "$this->page_url#XPath-" . base64_encode($this->page_xpath)),
                Text::make('Xpath', 'page_xpath')
                    ->readonly(),
                Code::make('Оригинал', 'html_eng')
                    ->readonly()
                    ->hideFromIndex(),
                Code::make('Текущее', 'html_rus_old')
                    ->hideFromIndex(),
                Code::make('Новое', 'html_rus_new')
                    ->hideFromIndex(),
            ]),
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
        return [
            app(StatisticCard::class)->withStatistic(),
        ];
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
