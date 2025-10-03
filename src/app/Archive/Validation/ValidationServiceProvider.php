<?php

namespace App\Archive\Validation;

use App\Archive\Validation\Contracts\ArchiveValidatorInterface;
use App\Archive\Validation\Services\ArchiveValidator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ArchiveValidatorInterface::class, ArchiveValidator::class);
    }
}
