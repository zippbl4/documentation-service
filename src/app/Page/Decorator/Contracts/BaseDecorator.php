<?php

namespace App\Page\Decorator\Contracts;

use App\Context\Context;
use App\Contracts\NameInterface;
use App\TemplateEngine\Contracts\TemplatesEngineContract;
use Doctrine\RST\Parser;
use Illuminate\Contracts\View\Factory as View;
use Illuminate\Mail\Markdown;
use Illuminate\View\Compilers\BladeCompiler;
use Psr\Log\LoggerInterface;

abstract class BaseDecorator implements NameInterface
{
    private LoggerInterface $logger;

    private View $view;

    private BladeCompiler $bladeCompiler;

    private TemplatesEngineContract $templatesEngine;

    private Context $context;

    private ?string $userCustomTemplate;

    abstract public function handle(string $content, \Closure $next): mixed;

    public function setContext(Context $context): self
    {
        $this->context = $context;
        return $this;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public static function getName(): string
    {
        return class_basename(static::class);
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function logger(): LoggerInterface
    {
        return $this->logger;
    }

    public function setView(View $view): void
    {
        $this->view = $view;
    }

    public function view(): View
    {
        return $this->view;
    }

    public function bladeCompiler(): BladeCompiler
    {
        return $this->bladeCompiler;
    }

    public function setBladeCompiler(BladeCompiler $bladeCompiler): void
    {
        $this->bladeCompiler = $bladeCompiler;
    }

    public function setTemplatesEngine(TemplatesEngineContract $templatesEngine): void
    {
        $this->templatesEngine = $templatesEngine;
    }

    public function renderView($view, $data = [], $mergeData = []): string
    {
        return $this->templatesEngine->renderView($view, $data, $mergeData);
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

    public function getUserCustomTemplate(): ?string
    {
        return $this->userCustomTemplate;
    }

    public function setUserCustomTemplate(?string $template): self
    {
        $this->userCustomTemplate = $template;
        return $this;
    }
}
