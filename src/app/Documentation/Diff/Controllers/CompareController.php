<?php

namespace App\Documentation\Diff\Controllers;

use App\Converter\Contracts\ConverterServiceContract;
use App\Documentation\Viewer\DTO\PageRequestDTO;
use App\Documentation\Viewer\Services\PageViewerInterface;
use App\TemplateEngine\Contracts\TemplatesEngineContract;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final readonly class CompareController
{
    public function __construct(
        private PageViewerInterface      $viewer,
        private TemplatesEngineContract $templates,
        private ConverterServiceContract $converter,
    ) {
    }

    public function compare(Request $request): View
    {
        $route = $request->route();
        $pageN = $route->parameter('pageN');
        $pageN = explode(';', $pageN);

        $articles = [];

        foreach ($pageN as $pageRequest) {
            // /wiki/compare/;{pageN}
            // /wiki/compare/;ru/matlab/R2018b/index.html;en/matlab/R2018b/index.html;en/matlab/R2018b/index.html;en/matlab/R2018b/index.html
            // теперь я знаю о безумии больше. много больше
            [$lang, $product, $version, $page] = explode('/', $pageRequest, 4);
            $route->setParameter('lang', $lang);
            $route->setParameter('product', $product);
            $route->setParameter('version', $version);
            $route->setParameter('page', $page);

            $p = $this
                ->viewer
                ->showPage($this->converter->convert($request, PageRequestDTO::class));

            $articles[] = [
                'title' => $p->getTitle(),
                'content' => $p->getContent(),
            ];
        }

        return $this->templates->getView('pages.compare', [
            'articles' => $articles,
        ]);
    }
}
