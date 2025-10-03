<?php

namespace App\Auth\UserProviders;

use App\Exceptions\NotImplementedException;
use App\Gateway\Contracts\GatewayAuthenticatableInterface;
use App\Gateway\DTO\Auth\LoginResponseObject;
use App\User\Contracts\UserRepositoryInterface;
use App\User\Entities\User;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

final class GwUserProvider implements UserProvider
{
    private ?LoginResponseObject $gwResponse = null;

    public function __construct(
        private readonly GatewayAuthenticatableInterface $gatewayAuthenticatable,
        private readonly UserRepositoryInterface         $userRepository,
    ) {
    }

    public function retrieveById($identifier): ?Authenticatable
    {
        return $this->userRepository->findById($identifier);
    }

    /**
     * @see EloquentUserProvider::retrieveByToken()
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        $model = new User();

        $retrievedModel = $model->newQuery()->where(
            $model->getAuthIdentifierName(), $identifier
        )->first();

        if (! $retrievedModel) {
            return null;
        }

        $rememberToken = $retrievedModel->getRememberToken();

        return $rememberToken && hash_equals($rememberToken, $token) ? $retrievedModel : null;
    }

    /**
     * @see EloquentUserProvider::updateRememberToken()
     * @param Authenticatable $user
     * @param $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token): void
    {
        $user->setRememberToken($token);
        $timestamps = $user->timestamps;
        $user->timestamps = false;
        $user->save();
        $user->timestamps = $timestamps;
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        if (empty($credentials)) {
            return null;
        }

        ['email' => $email, 'password' => $password] = $credentials;
        $user = $this->userRepository->findByEmail($email);

        $this->gwResponse = $gw = $this->gatewayAuthenticatable->login($email, $password);

        if ($gw->result !== null && !$gw->result) {
            return $user;
        }

        $me = $gw->me;

        if ($user === null) {
            $user = new User();
            $user->email = $email;
            $user->password = 'password';
        }

        $user->name = $gw->me->nickName ?: $email;
        $user->additional_data = $me;
        $this->userRepository->save($user);

        return $user;
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return empty($this->gwResponse?->errors);
    }
}
