<?php

namespace App\Menu\Services;

use App\Documentation\AspectPlugin\PageDriver\Entities\MysqlPage;
use App\TemplateEngine\Contracts\TemplatesEngineContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class MenuService
{
    public function __construct(private TemplatesEngineContract $templates)
    {
    }

    public function __invoke(Request $request): string
    {
        return $this->templates->renderBladeString("@include('{$this->templates->getCurrentTemplate()}.common.menu')", [
            'items' => $this->getItems($request),
        ]);
    }

    public function getItems(Request $request): Collection
    {
        return MysqlPage::query()
            ->whereNull('parent_id')
            ->with(['descendants' => fn($query) => $query->excludeColumns(['content'])])
            ->orderByDesc('id')
            ->excludeColumns(['content'])
            ->get()
        ;
    }
}
