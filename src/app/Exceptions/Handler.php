<?php

namespace App\Exceptions;

use App\TemplateEngine\Contracts\TemplatesEngineContract;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Queue\MaxAttemptsExceededException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->registerQueueExceptions();
        $this->registerRenderableExceptions();
    }

    private function registerQueueExceptions(): void
    {
        $this->reportable(function (MaxAttemptsExceededException $e): bool {
            return false;
        });
    }

    private function registerRenderableExceptions(): void
    {
        $this->renderable(function (DisplayableException $e, Request $request) {
            return new Response(
                $this
                    ->container
                    ->make(TemplatesEngineContract::class)
                    ->renderView('errors.404', ['message' => $e->getClientMessage()]),
                Response::HTTP_NOT_FOUND,
            );
        });
    }
}
