<?php

namespace App\Admin\Nova\Resources\Aspect;

use App\Admin\Nova\Actions\CloneAspect;
use App\Admin\Nova\Actions\DisableAspect;
use App\Admin\Nova\Actions\EnableAspect;
use App\Admin\Nova\Resource;
use App\Documentation\Aspect\Enums\StatusEnum;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class Aspect extends Resource
{
    use HasSortableRows;

    public static $perPageViaRelationship = 200;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Documentation\Aspect\Entities\Aspect>
     */
    public static string $model = \App\Documentation\Aspect\Entities\Aspect::class;

    public static $group = 'Аспекты';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    public function title(): string
    {
        return sprintf('%s (%s:%s:%s)', $this->name, $this->lang, $this->product, $this->version);
    }

    public static function label(): string
    {
        return 'Спецификация';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        $statusLabels = [
            StatusEnum::Disabled->value => 'Выкл',
            StatusEnum::Enabled->value => 'Вкл',
        ];

        return [
            ID::make(),
            Text::make('Название', 'name')
                ->required()
            ,
            Text::make('Язык', 'lang')
                ->required()
                ->default('ru|en')
                ->help('Поддерживаемые языки документации. Может быть регулярным выражением. Прим: ru|en; .*;')
            ,
            Text::make('Продукт', 'product')
                ->required()
                ->default('symfony')
                ->help('Поддерживаемые продукты документации. Может быть регулярным выражением. Прим: symfony; .*')
            ,
            Text::make('Версия|Пространство Wiki', 'version')
                ->required()
                ->default('6.*')
                ->help('Поддерживаемые версии документации. Может быть регулярным выражением. Прим: 6.*|7.*; .*')
            ,
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
            BelongsTo::make('Путь', 'path', Path::class)
                ->showCreateRelationButton()
                ->help('Выбрать существующий путь ▲ или создать новый ▼')
                ->showOnIndex(false)
                ->showOnDetail(false)
            ,
            BelongsTo::make('Родительский аспект', 'parent', Aspect::class)
                ->showOnIndex(false)
                ->showOnDetail(false)
                ->required(false)
                ->nullable()
            ,
            HasOne::make('Родительский аспект', 'parent', Aspect::class)
                ->onlyOnDetail()
            ,
            HasOne::make('Путь', 'path', Path::class)
                ->onlyOnDetail()
            ,
            HasMany::make('Настройки', 'settings', AspectConfig::class),
            HasMany::make('Мапперы', 'mappers', Mapper::class),
            HasMany::make('Декораторы', 'decorators', Decorator::class),
            HasMany::make('Валидаторы', 'validations', Validation::class),
            HasMany::make('Продукты', 'products', Product::class),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            new DisableAspect(),
            new EnableAspect(),
            new CloneAspect(),
        ];
    }
}
