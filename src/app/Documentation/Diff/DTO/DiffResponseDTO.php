<?php

namespace App\Documentation\Diff\DTO;

/**
 * @deprecated
 */
class DiffResponseDTO
{
    public function __construct(
        private string $from,
        private string $to,
        private string $diff,
    ) {
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getDiff(): string
    {
        return $this->diff;
    }
}
