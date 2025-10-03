<?php

namespace App\Admin\Nova\Resources\Aspect;

use App\Admin\Nova\Actions\DisableAspect;
use App\Admin\Nova\Actions\EnableAspect;
use App\Admin\Nova\Resource;
use App\Documentation\Aspect\DTO\AspectConfigDTO;
use App\Documentation\Aspect\Enums\StatusEnum;
use App\Page\Decorator\Contracts\SupportedDecoratorsInterface;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;
use Pavloniym\ActionButtons\ActionButton;

class AspectConfig extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Documentation\Aspect\Entities\AspectConfig>
     */
    public static string $model = \App\Documentation\Aspect\Entities\AspectConfig::class;

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
        return 'Настройки';
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

        $supportedSettings = AspectConfigDTO::getSupportedSettings();
        $keys = array_keys($supportedSettings);
        $settings = array_combine($keys, $keys);

        return [
            ID::make(),
            BelongsTo::make('Спецификация', 'aspect', Aspect::class),
            Text::make('Описание', 'description')
                ->dependsOn(['name'], function (Text $field, NovaRequest $request, FormData $formData) use ($supportedSettings) {
                    $name = $formData->name;

                    if (! empty($name)) {
                        $field
                            ->default($supportedSettings[$name])
                        ;
                    }
                })
            ,
            Select::make('Название', 'name')
                ->options($settings)
                ->required()
            ,
            Text::make('Значение', 'value'),
            Select::make('Статус', 'status')
                ->required()
                ->help('Статус отображения')
                ->default(StatusEnum::Disabled->value)
                ->options($statusLabels)
                ->displayUsingLabels()
                ->onlyOnForms()
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
