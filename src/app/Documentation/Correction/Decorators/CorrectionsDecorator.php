<?php

namespace App\Documentation\Correction\Decorators;

use App\Config\DTO\ConfigDTO;
use App\Documentation\Correction\Contracts\CorrectionsServiceInterface;
use App\Page\Decorator\Contracts\BaseDecorator;
use Ramsey\Uuid\Uuid;

final class CorrectionsDecorator extends BaseDecorator
{
    public function __construct(
        private readonly ConfigDTO                      $config,
        private readonly CorrectionsServiceInterface    $correctionsService,
    ) {
    }

    /**
     * @TODO рефакторинг
     */
    public function handle(string $content, \Closure $next): mixed
    {
        $uuid = Uuid::uuid4()->toString();
        $tag1 = "<!--fast_edit_beg_$uuid-->";
        $tag2 = "<!--fast_edit_end_$uuid-->";
        $content = str_replace(
            "</body>",
            "$tag1$tag2\n\n</body>",
            $content
        );

        $requestPage = $this->getContext()->value('request');

        $releaseName = $requestPage->version;
        $lang = $requestPage->lang;
        $product = $requestPage->product;

        $engPage = route('docs.show.page', [
            // TODO язык
            'lang' => 'eng',
            'product' => $product,
            'version' => $releaseName,
            'page' => $requestPage->getPageWithExtension(),
        ]);
        $rusPage = route('docs.show.page', [
            'lang' => $lang,
            'product' => $product,
            'version' => $releaseName,
            'page' => $requestPage->getPageWithExtension(),
        ]);
        $apiPage = route('api.corrections.store', [
            'lang' => 'rus',
            'product' => $product,
            'version' => $releaseName,
            'page' => $requestPage->getPageWithExtension(),
        ]);
        $popupWithRules_head = $this->config->popupWithRulesHead;
        $popupWithRules_body = $this->config->popupWithRulesBody;
        $isCurrentRel = 'true';
        $tooltipTimeout = $this->config->tooltipTimeout;
        $correctionsSelectors = $this->config->correctionsSelectors;
        $scripts = $this->config->scriptsBody;
        // TODO только для авторизованных пользаков
        $edits = json_encode($this->correctionsService->getApprovedCorrections($rusPage));

        $body = <<<HTML
<link rel="stylesheet" href="/css/protip.min.css">
<link rel="stylesheet" href="/css/corrections.css">
<script>
	window.matlab_release_name = "{$releaseName}";
	window.matlab_eng_page = "{$engPage}";
	window.matlab_api_page = "{$apiPage}";
	window.matlab_mark_page = "{$rusPage}";
	window.matlab_current_edits = $edits;
	window.matlab_is_current_release = {$isCurrentRel};
	window.matlab_corrections_selectors = "{$correctionsSelectors}";
	window.matlab_tooltip_timeout = "{$tooltipTimeout}";
</script>
<script src="/js/protip.min.js"></script>
<script src="/js/readonlymarks.js?v=2"></script>
<script src="/js/corrections.js?v=2"></script>
HTML;

        $content = preg_replace(
            "#$tag1(.*)$tag2#",
            "$tag1\n$1\n$body\n$scripts\n$tag2",
            $content
        );

        return $next($content);
    }
}
