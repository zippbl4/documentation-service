<?php

namespace App\Documentation\Correction\Controllers;

use App\Documentation\Correction\Contracts\CorrectionsServiceInterface;
use App\Http\Requests\Api\CorrectionStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

readonly class CorrectionsController
{
    public function __construct(
        private CorrectionsServiceInterface $correctionsService
    ) {
    }

    public function store(CorrectionStoreRequest $request): JsonResponse
    {
        $dto = $request->getDTO();
        $user = Auth::user();

        $this->correctionsService->store($user, $dto);

        return new JsonResponse([
            'message' => "Новая правка успешно добавлена!",
        ]);
    }

}
