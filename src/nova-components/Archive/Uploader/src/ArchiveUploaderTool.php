<?php

namespace App\Archive\Uploader;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class ArchiveUploaderTool extends Tool
{
    public const NAME = 'archive-uploader';

    public static function getToolPath(): string
    {
        return __DIR__;
    }

    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot(): void
    {
        Nova::script(self::NAME, __DIR__.'/../dist/js/tool.js');
        Nova::style(self::NAME, __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function menu(Request $request): mixed
    {
        return MenuSection::make('Загрузить архив')
            ->path('/' . self::NAME)
            ->icon('server');
    }
}
