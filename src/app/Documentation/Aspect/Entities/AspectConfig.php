<?php

namespace App\Documentation\Aspect\Entities;

use App\Documentation\Aspect\Enums\StatusEnum;
use App\Documentation\Aspect\Events\AspectConfigSaved;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property int $id
 * @property string $description
 * @property string $name
 * @property null|string $value
 * @property StatusEnum $status
 * @property Aspect $aspect
 */
class AspectConfig extends Model
{
    use HasFactory;

    protected $table = 'aspect_settings';

    protected $guarded = [];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    protected $dispatchesEvents = [
        'saving' => AspectConfigSaved::class,
    ];

    public function aspect(): BelongsTo
    {
        return $this->belongsTo(Aspect::class);
    }
}
