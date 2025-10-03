<?php

namespace App\Documentation\Diff\Controllers;

use App\ContentEngine\Contracts\DiffInterface;
use App\Converter\Contracts\ConverterServiceContract;
use App\Documentation\Diff\Services\PageDiffService;
use App\Documentation\Viewer\DTO\PageRequestDTO;
use App\Documentation\Viewer\DTO\PageResponseDTO;
use App\Documentation\Viewer\Services\PageViewerInterface;
use App\TemplateEngine\Contracts\TemplatesEngineContract;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final readonly class DiffController
{
    public function __construct(
        private PageViewerInterface      $viewer,
        private PageDiffService          $diffService,
        private DiffInterface            $diff,
        private TemplatesEngineContract  $templates,
        private ConverterServiceContract $converter,
    ) {
    }

    public function diff(Request $request): View
    {
        $route = $request->route();

        /** @var PageResponseDTO[] $pages */
        $pages = [];
        for ($i = 0; $i <= 1; $i++) {
            // /wiki/diff/;{page0};{page1}
            // /wiki/diff/;ru/matlab/R2018b/index.html;en/matlab/R2018b/index.html
            // теперь я знаю о безумии больше
            [$lang, $product, $version, $page] = explode('/', $route->parameter('page' . $i), 4);
            $route->setParameter('lang', $lang);
            $route->setParameter('product', $product);
            $route->setParameter('version', $version);
            $route->setParameter('page', $page);
            $pages[] = $this
                ->viewer
                ->showPage($this->converter->convert($request, PageRequestDTO::class));
        }

        [$page0, $page1] = $pages;

        $diff = $this->diff->diff(
            $page0->getContent(),
            $page1->getContent(),
        );

        $articles[] = [
            'title' => $page0->getTitle(),
            'content' => $page0->getContent(),
        ];

        $articles[] = [
            'title' => $page0->getTitle() . ' <-> ' . $page1->getTitle(),
            'content' => $diff,
        ];

        $articles[] = [
            'title' => $page1->getTitle(),
            'content' => $page1->getContent(),
        ];

        return $this->templates->getView('pages.compare', [
            'articles' => $articles,
        ]);
    }
}
