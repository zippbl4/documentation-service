<?php

namespace App\TemplateEngine\Contracts;

use Illuminate\Contracts\View\View;

interface TemplatesEngineContract
{
    public function renderView($view, $data = [], $mergeData = []): string;
    public function getView($view, $data = [], $mergeData = []): View;
    public function getCurrentTemplate(): string;
}
