<?php

namespace App\Documentation\Aspect\Entities;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $name
 * @property string $pattern
 * @property string $map_from
 * @property string $map_to
 */
class Mapper extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function aspect(): BelongsTo
    {
        return $this->belongsTo(Aspect::class);
    }

    public function mapFrom(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ?? '',
        );
    }
}
