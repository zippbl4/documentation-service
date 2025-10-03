<?php

namespace App\Admin\Nova\Resources\Aspect;

use App\Admin\Nova\Resource;
use App\Config\DTO\ConfigDTO;
use App\Documentation\Aspect\DTO\AspectPathDTO;
use App\Page\Driver\Contracts\SupportedDriversInterface;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Path extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Documentation\Aspect\Entities\Path>
     */
    public static string $model = \App\Documentation\Aspect\Entities\Path::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'pattern';

    public static $group = 'Аспекты';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    public function title(): string
    {
        return sprintf('%s (%s:%s:%s)', $this->name, $this->driver, $this->root, $this->pattern);
    }

    public static function label(): string
    {
        return 'Пути';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $drivers = app(SupportedDriversInterface::class)->getSupportedDrivers();
        $drivers = array_combine($drivers, $drivers);

        $config = app(ConfigDTO::class);

        return [
            ID::make(),
            Text::make('Описание', 'name')
                ->required()
            ,
            Select::make('Драйвер поиска контента', 'driver')
                ->required()
                ->options($drivers)
            ,
            Text::make('Корень файловой системы', 'root')
                ->default('app-config.release_folder')
                ->help(sprintf('app-config.release_folder: %s', $config->releaseFolder))
            ,
            Text::make('Паттерн поиска в файловой системе', 'pattern')
                ->default('/' . implode('/' , AspectPathDTO::getSupportedPatterns()))
                ->help('Доступные паттерны: ' . implode(', ' , AspectPathDTO::getSupportedPatterns()))
            ,
//            Text::make('TДанные для входа', 'credentials')
//                ->hide()
//                ->dependsOn(['driver'], function (Text $field, NovaRequest $request, FormData $formData) {
//                    if ($formData->driver === RemoteDriver::getName()) {
//                        $field
//                            ->show()
//                            ->required()
//                        ;
//                    }
//                })
//            ,
            Code::make('nginx', 'nginx_conf_template')
                ->default('-')
            ,

            HasMany::make('Прикрепленные спецификации', 'aspects', Aspect::class)
            ,
        ];
    }
}
