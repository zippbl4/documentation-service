<?php

namespace App\Archive\Validation\Contracts;

use App\Archive\Validation\DTO\ContextDTO;

interface ArchiveValidatorInterface
{
    public function make(ContextDTO $context, array $rules = []): ArchiveValidatorInterface;
    public function passes(): bool;
    public function fails(): bool;
    public function getMessages(): array;
}
