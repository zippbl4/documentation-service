<?php

namespace App\User\Contracts;

use App\User\Entities\User;
use Illuminate\Contracts\Auth\Authenticatable;

interface UserRepositoryInterface
{
    public function findById(int $id): ?Authenticatable;

    public function findByEmail(string $email): ?Authenticatable;

    public function save(User $entity): void;
}
