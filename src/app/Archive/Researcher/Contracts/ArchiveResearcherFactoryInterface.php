<?php

namespace App\Archive\Researcher\Contracts;

interface ArchiveResearcherFactoryInterface
{
    public function get(string $name): BaseResearcherStrategy;
}
