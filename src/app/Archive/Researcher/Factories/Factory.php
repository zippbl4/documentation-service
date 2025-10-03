<?php

namespace App\Archive\Researcher\Factories;

use App\Archive\Researcher\Contracts\ArchiveResearcherFactoryInterface;
use App\Archive\Researcher\Contracts\BaseResearcherStrategy;

final class Factory implements ArchiveResearcherFactoryInterface
{
    private array $strategies = [];

    public function add(string $name, BaseResearcherStrategy $strategy): void
    {
        $this->strategies[$name] = $strategy;
    }

    public function get(string $name): BaseResearcherStrategy
    {
        if (!isset($this->strategies[$name])) {
            throw new \InvalidArgumentException(
                "No getter: '{$name}'"
            );
        }

        return $this->strategies[$name];
    }
}
