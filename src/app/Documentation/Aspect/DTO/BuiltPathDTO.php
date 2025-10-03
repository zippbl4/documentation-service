<?php

namespace App\Documentation\Aspect\DTO;

final class BuiltPathDTO
{
    private array $filters = [];
    private array $patterns = [];
    private string $rootWithPath = '';
    private string $root = '';
    private string $path = '';

    public function __construct()
    {
    }


    public function addPattern(string $key, string $val): self
    {
        $this->patterns[$key] = $val;

        return $this;
    }

    public function addFilter(string $key, string $val): self
    {
        $this->filters[$key] = $val;

        return $this;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function setRoot(string $root): self
    {
        $this->root = $root;

        return $this;
    }

    public function setRootWithPath(string $rootWithPath): self
    {
        $this->rootWithPath = $rootWithPath;

        return $this;
    }

    public function getPatterns(): array
    {
        return $this->patterns;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getRoot(): string
    {
        return $this->root;
    }

    public function getRootWithPath(): string
    {
        return $this->rootWithPath;
    }
}
