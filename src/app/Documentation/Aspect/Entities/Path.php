<?php

namespace App\Documentation\Aspect\Entities;

use App\Documentation\Aspect\Enums\StatusEnum;
use App\Documentation\Aspect\Events\PathSaved;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|StatusEnum $status
 * @property string $driver - local
 * @property string $root - app-config.release_folder
 * @property string $pattern - /{version}/{version}_{lang}/{product}/{page}
 * @property string $name - Документация по матлабу версии R2019*
 * @property string $nginx_conf_template
 */
class Path extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dispatchesEvents = [
        'saved' => PathSaved::class,
    ];

    public function aspects(): HasMany
    {
        return $this->hasMany(Aspect::class);
    }

    public function root(): Attribute
    {
        // Рут может быть пустым для eloquent driver
        return Attribute::make(
            set: fn (?string $value) => $value ?? '',
        );
    }
}
