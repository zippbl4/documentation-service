<?php

namespace App\Config\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $val
 * @property string|null $description
 */
class Settings extends Model
{
    protected $guarded = [
        'id',
        'updated_at',
    ];
}
