<?php

namespace App\Documentation\AspectPlugin\Product;

use App\Documentation\Aspect\Entities\Aspect;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $archive_hash
 * @property string $job_uuid
 * @property string $root_folder
 * @property string $root_path
 * @property string $lang
 * @property string $version
 * @property string $product
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ProductEntity extends Model
{
    protected $table = 'products';
    protected $guarded = [];

    public function aspect(): BelongsTo
    {
        return $this->belongsTo(Aspect::class);
    }

    public function getAspect(): ?Aspect
    {
        return $this->aspect;
    }
}
