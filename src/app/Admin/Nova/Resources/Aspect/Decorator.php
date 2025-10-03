<?php

namespace App\Admin\Nova\Resources\Aspect;

use App\Admin\Nova\Actions\DisableAspect;
use App\Admin\Nova\Actions\EnableAspect;
use App\Admin\Nova\Resource;
use App\Documentation\Aspect\Enums\StatusEnum;
use App\Page\Decorator\Contracts\SupportedDecoratorsInterface;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;
use Pavloniym\ActionButtons\ActionButton;

class Decorator extends Resource
{
    use HasSortableRows;

    public static $sortableCacheEnabled = false;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Documentation\Aspect\Entities\Decorator>
     */
    public static string $model = \App\Documentation\Aspect\Entities\Decorator::class;

//    public static $relatableSearchResults = 200;

    public static $perPageViaRelationship = 200;

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
        return 'Декораторы';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        $decorators = app(SupportedDecoratorsInterface::class)->getSupportedDecorators();
        $decorators = array_combine($decorators, $decorators);

        $statusLabels = [
            StatusEnum::Disabled->value => 'Выкл',
            StatusEnum::Enabled->value => 'Вкл',
        ];

        return [
            ID::make(),
            BelongsTo::make('Спецификация', 'aspect', Aspect::class),
            Select::make('name')->options($decorators),
            Select::make('Статус', 'status')
                ->required()
                ->help('Статус отображения')
                ->default(StatusEnum::Disabled->value)
                ->options($statusLabels)
                ->displayUsingLabels()
                ->onlyOnForms()
            ,
            Code::make('Пользовательский ввод', 'user_custom_template')
                ->hide()
                ->language('blade')
                ->dependsOn(['name'], function (Code $field, NovaRequest $request, FormData $formData) {
                    $name = $formData->name;

                    if ($name && app(SupportedDecoratorsInterface::class)->hasUserInput($name)) {
                        $field->show();
                    }
                })
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

    public function actions(NovaRequest $request): array
    {
        return [
            new DisableAspect(),
            new EnableAspect(),
        ];
    }
}
