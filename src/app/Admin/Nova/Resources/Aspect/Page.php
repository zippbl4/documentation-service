<?php

namespace App\Admin\Nova\Resources\Aspect;

use Advoor\NovaEditorJs\NovaEditorJsField;
use App\Admin\Nova\Resource;
use App\Config\DTO\ConfigDTO;
use App\Config\Enums\FeatureFlagEnum;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Page extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Documentation\AspectPlugin\PageDriver\Entities\MysqlPage>
     */
    public static string $model = \App\Documentation\AspectPlugin\PageDriver\Entities\MysqlPage::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    public static $group = 'Настройки';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'lang',
        'product',
        'version',
        'page',
    ];


    public static function label(): string
    {
        return 'Страницы';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $config = app(ConfigDTO::class);

        return [
            ID::make(),
            BelongsTo::make('Аспект', 'aspect', Aspect::class)
                ->showOnIndex(false)
                ->showOnDetail(false)
            ,
            BelongsTo::make('Родитель', 'parent', Page::class)
                ->searchable()
                ->showOnIndex(false)
                ->showOnDetail(false)
                ->required(false)
                ->nullable()
            ,
            new Panel('Мета', [
                Text::make('Язык продукта', 'lang')
                    ->dependsOn(...$this->dependsOnAspect('lang'))
                ,
                Text::make('Продукт', 'product')
                    ->dependsOn(...$this->dependsOnAspect('product'))
                ,
                Text::make('Версия продукта|Пространство Wiki', 'version')
                    ->dependsOn(...$this->dependsOnAspect('version'))
                ,
                Text::make('Вложенность', 'part')
                    ->help('Пример: index.html; matlab_external')
                ,
                Text::make('URL страницы', 'page')
                    ->help('Пример: index.html; matlab_external/work-with-members-of-a-net-enumeration.html')
                ,
            ])
            ,
            new Panel('Контент', [
                Text::make('Заголовок', 'title'),
                $config->editorJsFeatureFlag === FeatureFlagEnum::Disabled
                    ? Textarea::make('Статья', 'content')
                    : NovaEditorJsField::make('Статья', 'content')
                ,
            ])
            ,
        ];
    }

    private function dependsOnAspect(string $aspectField): array
    {
        return [
            ['aspect'],
            function (Text $field, NovaRequest $request, FormData $formData) use ($aspectField) {
//                $aspectId = $formData->aspect;
//                if ($aspectId) {
//                    $aspect = app(AspectRepositoryInterface::class)->findById($aspectId);
//                    $field->value = $aspect->$aspectField;
//                }
            },
        ];
    }

    private function dependsOnParentPage(): array
    {
        return [
            ['parent'],
            function (Text $field, NovaRequest $request, FormData $formData) {
//                $aspectId = $formData->aspect;
//                if ($aspectId) {
//                    $aspect = app(AspectRepositoryInterface::class)->findById($aspectId);
//                    $field->value = $aspect->$aspectField;
//                }
            },
        ];
    }
}
