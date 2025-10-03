<?php

namespace App\User;

use App\User\Contracts\UserRepositoryInterface;
use App\User\Contracts\UserNotificationServiceInterface;
use App\User\Repositories\UserRepository;
use App\User\Services\UserNotificationService;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserNotificationServiceInterface::class, UserNotificationService::class);
    }
}
