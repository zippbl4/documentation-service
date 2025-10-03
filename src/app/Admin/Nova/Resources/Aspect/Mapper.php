<?php

namespace App\Admin\Nova\Resources\Aspect;

use App\Admin\Nova\Actions\DisableAspect;
use App\Admin\Nova\Actions\EnableAspect;
use App\Admin\Nova\Resource;
use App\Documentation\Aspect\DTO\AspectPathDTO;
use App\Documentation\Aspect\Enums\StatusEnum;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Pavloniym\ActionButtons\ActionButton;

class Mapper extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Documentation\Aspect\Entities\Mapper>
     */
    public static string $model = \App\Documentation\Aspect\Entities\Mapper::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    public static $group = 'Аспекты';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    public static function label(): string
    {
        return 'Мапперы';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        $statusLabels = [
            StatusEnum::Disabled->value => 'Выкл',
            StatusEnum::Enabled->value => 'Вкл',
        ];

        $patterns = AspectPathDTO::getSupportedPatterns();
        $patterns = array_combine($patterns, $patterns);

        return [
            ID::make(),
            BelongsTo::make('Спецификация', 'aspect', Aspect::class)
                ->required()
            ,
            Text::make('name')
                ->required()
            ,
            Select::make('pattern')
                ->options($patterns)
                ->required()
            ,
            Text::make('map_from'),
            Text::make('map_to'),
            Select::make('Статус', 'status')
                ->required()
                ->help('Статус отображения')
                ->default(StatusEnum::Disabled->value)
                ->options($statusLabels)
                ->displayUsingLabels()
                ->onlyOnIndex()
            ,
            ActionButton::make('')
                ->icon('')
                ->text($this->resource->status === StatusEnum::Disabled ? 'Вкл' : 'Выкл')
                ->action($this->resource->status === StatusEnum::Disabled ? new EnableAspect() : new DisableAspect(), $this->resource->id)
                ->onlyOnIndex()
            ,
            Badge::make('Статус', 'status')
                ->map([
                    StatusEnum::Disabled->value => 'danger',
                    StatusEnum::Enabled->value => 'success',
                ])
                ->labels($statusLabels)
            ,
        ];
    }
}
