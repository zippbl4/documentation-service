<?php

namespace App\Admin\Nova\Resources\Aspect;

use App\Admin\Nova\Actions\RunDatabaseSaverAction;
use App\Admin\Nova\Actions\RunReindexAction;
use App\Admin\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Pavloniym\ActionButtons\ActionButton;

class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Documentation\AspectPlugin\Product\ProductEntity>
     */
    public static string $model = \App\Documentation\AspectPlugin\Product\ProductEntity::class;

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
        return 'Продукты';
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
            ActionButton::make('')
                ->icon('')
                ->text('Загрузка в БД')
                ->action(new RunDatabaseSaverAction(), $this->resource->id)
            ,

            ActionButton::make('')
                ->icon('')
                ->text('Реиндексация')
                ->action(new RunReindexAction(), $this->resource->id)
            ,

            ID::make(),
            BelongsTo::make('Спецификация', 'aspect', Aspect::class),
            // TODO больно так делать но без слеша картинка не грузится
            URL::make('', fn () => route('docs.show.page', ['lang' => $this->lang, 'product' => $this->product, 'version' => $this->version]) . '/')
                ->displayUsing(fn () => "Перейти")
            ,
            Text::make('lang'),
            Text::make('product'),
            Text::make('version'),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            new RunReindexAction(),
            new RunDatabaseSaverAction(),
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
       return false;
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return false;
    }

    public function authorizedToDelete(Request $request): bool
    {
        return false;
    }
}
