<?php

namespace App\Menu\Composers;

use App\Menu\Services\MenuService;
use Illuminate\View\View;

readonly class MenuComposer
{
    public function __construct(private MenuService $menu)
    {
    }

    public function compose(View $view): void
    {
        $view->with('menu', $this->menu);
    }
}
