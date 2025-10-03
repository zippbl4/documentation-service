<?php

namespace App\Documentation\Viewer\Controllers;

use App\Converter\Contracts\ConverterServiceContract;
use App\Dictionary\ProductDictionary;
use App\Documentation\Aspect\Contracts\AspectServiceContract;
use App\Documentation\Aspect\DTO\AspectIdDTO;
use App\Documentation\AspectPlugin\PageDriver\Entities\MysqlPage;
use App\Documentation\Viewer\DTO\WikiRequestDTO;
use App\Documentation\Viewer\Services\PageViewerInterface;
use App\TemplateEngine\Contracts\TemplatesEngineContract;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final readonly class WikiController
{
    public function __construct(
        private TemplatesEngineContract  $templates,
        private PageViewerInterface      $pageManager,
        private ConverterServiceContract $converter,
        private AspectServiceContract $aspectService,
    ) {
    }

    public function index(): View
    {
        return $this->templates->getView('pages.index');
    }

    public function create(Request $request, string $lang, ?string $page = null): View
    {
        return $this->templates->getView('pages.create', [
            'lang' => $lang,
            'page' => $page,
        ]);
    }

    public function store(Request $request, string $lang): RedirectResponse
    {
        // TODO
        // Вынести все это говнище в сервис
        $aspect = $this->aspectService->getAspectByAspectId(new AspectIdDTO(
            lang: $lang,
            product: ProductDictionary::WIKI,
            version: ProductDictionary::WIKI_DEFAULT_SPACE,
        ));

        $page = Str::slug($request->title);
        $parent = null;
        if ($request->parent) {
            /** @var MysqlPage $page */
            $parent = MysqlPage::query()
                ->where([
                    'aspect_id' => $aspect->entityId,
                    'lang' => $lang,
                    'product' => ProductDictionary::WIKI,
                    'version' => ProductDictionary::WIKI_DEFAULT_SPACE,
                    'page'=> $request->parent,
                ])
                ->firstOrFail()
            ;
            $page = $parent->part . '/' . $page;
        }

        /** @var MysqlPage $page */
        $page = MysqlPage::query()
            ->create([
                'aspect_id' => $aspect->entityId,
                'parent_id' => $parent?->id,

                'lang' => $lang,
                'product' => ProductDictionary::WIKI,
                'version' => ProductDictionary::WIKI_DEFAULT_SPACE,
                'part' => Str::slug($request->title),
                'page'=>  $page,

                'title' => $request->title,
                'content' => $request->input('content'),
            ]);

        return new RedirectResponse($page->getRoute());
    }

    public function show(Request $request): View
    {
        $response = $this
            ->pageManager
            ->showPage($wikiRequest = $this->converter->convert($request, WikiRequestDTO::class))
        ;

        return $this->templates->getView('pages.show', [
            'request' => $wikiRequest,
            'title' => $response->getTitle(),
            'content' => $response->getContent(),
            'context' => $response->getContext(),
        ]);
    }

    public function edit(string $lang, string $page, Request $request): View
    {
        $response = $this
            ->pageManager
            ->showPage($wikiRequest = $this->converter->convert($request, WikiRequestDTO::class))
        ;

        return $this->templates->getView('pages.edit', [
            'request' => $wikiRequest,
            'context' => $response->getContext(),

            'title' => $response->getTitle(),
            'content' => $response->getContent(),
            'page' => $page,
        ]);
    }

    public function update(string $lang, Request $request): RedirectResponse
    {
        // TODO
        // Вынести все это говнище в сервис
        $aspect = $this->aspectService->getAspectByAspectId(new AspectIdDTO(
            lang: $lang,
            product: ProductDictionary::WIKI,
            version: ProductDictionary::WIKI_DEFAULT_SPACE,
        ));

        $id = [
            'aspect_id' => $aspect->entityId,
            'lang' => $lang,
            'product' => ProductDictionary::WIKI,
            'version' => ProductDictionary::WIKI_DEFAULT_SPACE,
            'page' => $request->page,
        ];

        /** @var MysqlPage $page */
        $page = MysqlPage::query()
            ->where($id)
            ->first()
        ;

        $title = Str::slug($request->title);
        $attributes = [
            'title' => $request->title,
            'content' => $request->input('content'),
            'part' => $title,
            'page' => $title,
        ];

        /** @var ?MysqlPage $parent */
        $parent = $page->parent;
        if ($parent !== null) {
            $attributes['page'] = $parent->page . '/' . $title;
        }

        $page->update($attributes);

        return new RedirectResponse($page->getRoute());
    }
}
