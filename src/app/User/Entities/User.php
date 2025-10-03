<?php

namespace App\User\Entities;

use App\User\Contracts\UserContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property-read string $nickName
 * @property string $email
 * @property string $password
 * @property-read string $gwId
 * @property-read string $crmId
 * @property-read string $token
 * @property array $additional_data
 */
class User extends Authenticatable implements UserContract
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'additional_data',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'additional_data' => 'array',
    ];

    public function getId(): int
    {
        return $this->id;
    }
    protected function gwId(): Attribute
    {
        return $this->getAttributeFromAdditionalData('gwId');
    }

    protected function crmId(): Attribute
    {
        return $this->getAttributeFromAdditionalData('crmId');
    }

    protected function token(): Attribute
    {
        return $this->getAttributeFromAdditionalData('token');
    }

    protected function nickName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) =>  $this->name,
        );
    }

    protected function getAttributeFromAdditionalData(string $attribute): object
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $this->additional_data[$attribute],
        );
    }
}
