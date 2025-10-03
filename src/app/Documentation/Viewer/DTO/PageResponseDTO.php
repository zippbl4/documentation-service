<?php

namespace App\Documentation\Viewer\DTO;

use App\Context\Context;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;

final class PageResponseDTO implements Responsable
{
    public function __construct(
        private readonly string $title,
        private readonly string $content,
        private readonly ?Context $context,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getContext(): ?Context
    {
        return $this->context;
    }

    public function toResponse($request): Response
    {
        return new Response(
            $this->getContent(),
            200,
            ['Content-Type' => 'text/html'],
        );
    }
}
