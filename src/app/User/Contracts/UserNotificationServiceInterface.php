<?php

namespace App\User\Contracts;

use Illuminate\Notifications\Notification;

interface UserNotificationServiceInterface
{
    public function sendNotificationToAdmins(Notification $notification): void;
}
