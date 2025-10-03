<?php

namespace App\Documentation\Aspect\Entities;

use App\Documentation\Aspect\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property int $id
 * @property string $name
 * @property null|string $user_custom_template
 */
class Decorator extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    protected $guarded = [];

    public function aspect(): BelongsTo
    {
        return $this->belongsTo(Aspect::class);
    }
}
