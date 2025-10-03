<?php

namespace App\Aspect;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class AspectTool extends Tool
{
    public const NAME = 'aspect';

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
    public function menu(Request $request): MenuSection
    {
        return MenuSection::make('Спецификация')
            ->path('/' . self::NAME)
            ->icon('server');
    }
}
