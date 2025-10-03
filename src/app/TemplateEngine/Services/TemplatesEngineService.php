<?php

namespace App\TemplateEngine\Services;

use App\Config\DTO\ConfigDTO;
use App\TemplateEngine\Contracts\TemplatesEngineContract;
use Doctrine\RST\Parser;
use Illuminate\Contracts\View\Factory as View;
use Illuminate\Mail\Markdown;
use Illuminate\View\Compilers\BladeCompiler;
use Psr\Log\LoggerInterface;

class TemplatesEngineService implements TemplatesEngineContract
{
    private string $currentTemplate = '';

    public function __construct(
        private LoggerInterface $logger,
        private ConfigDTO $config,
        private View $view,
    ) {
        $this->currentTemplate = $this->config->template;
    }

    public function getCurrentTemplate(): string
    {
        return $this->currentTemplate;
    }

    public function setCurrentTemplate(string $template): self
    {
        $this->currentTemplate = $template;

        return $this;
    }

    public function renderView($view, $data = [], $mergeData = []): string
    {
        return $this->view->make($this->getViewName($view), $data, $mergeData)->render();
    }

    public function getView($view, $data = [], $mergeData = []): \Illuminate\Contracts\View\View
    {
        return $this->view->make($this->getViewName($view), $data, $mergeData);
    }

    /** @deprecated  */
    public function renderBladeString(string $view, $data = []): string
    {
        return BladeCompiler::render($view, $data);
    }

    /** @deprecated  */
    public function renderMarkdownString(string $view): string
    {
        return Markdown::parse($view);
    }

    /** @deprecated  */
    public function renderRstString(string $view): string
    {
        return (new Parser())->parse($view)->renderDocument();
    }

    private function getViewName(string $view): string
    {
        $path = sprintf(
            '%s.%s',
            $this->getCurrentTemplate(),
            $view,
        );

        if (! $this->view->exists($path) && $this->getCurrentTemplate() !== 'default') {
            $this->setCurrentTemplate('default');

            return $this->getViewName($view);
        }

        return $path;
    }
}
