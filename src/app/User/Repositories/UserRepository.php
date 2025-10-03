<?php

namespace App\User\Repositories;

use App\User\Contracts\UserRepositoryInterface;
use App\User\Entities\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return $this->getBuilder()->where('id', $id)->first();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->getBuilder()->where('email', $email)->first();
    }

    public function save(User $entity): void
    {
        $entity->save();
    }

    private function getBuilder(): Builder
    {
        return (new User())->newModelQuery();
    }
}
