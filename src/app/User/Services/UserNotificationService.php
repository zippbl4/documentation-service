<?php

namespace App\User\Services;

use App\User\Contracts\UserNotificationServiceInterface;
use App\User\Contracts\UserRepositoryInterface;
use App\User\Entities\User;
use Illuminate\Notifications\Notification;

class UserNotificationService implements UserNotificationServiceInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function sendNotificationToAdmins(Notification $notification): void
    {
        // TODO
        /** @var User $user */
        $user = $this->userRepository->findByEmail('admin@mail.ru');
        $user->notify($notification);
    }
}
