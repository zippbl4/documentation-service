<?php

namespace App\Documentation\Correction\Contracts;

use App\Documentation\Correction\DTO\CorrectionDTO;
use App\User\Entities\User;

interface CorrectionsServiceInterface
{
    public function store(User $user, CorrectionDTO $correction): void;
    public function getApprovedCorrections(string $releaseUrl): array;
}
