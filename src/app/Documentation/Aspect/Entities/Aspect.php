<?php

namespace App\Documentation\Aspect\Entities;

use App\Documentation\Aspect\Casts\AspectConfigValueCast;
use App\Documentation\Aspect\DTO\AspectIdDTO;
use App\Documentation\Aspect\Enums\StatusEnum;
use App\Documentation\Aspect\Events\AspectCreated;
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
 * @property string $name
 * @property string $lang
 * @property string $product
 * @property string $version
 * @property StatusEnum $status
 * @property Aspect $parent
 * @property Aspect[]|Collection $children
 * @property Path $path
 * @property Collection|Decorator[] $decorators
 * @property Collection|Validation[] $validations
 * @property Collection|Mapper[] $mappers
 * @property Collection|AspectConfig[] $settings
 * @see \App\Documentation\AspectPlugin\AspectPluginServiceProvider::registerDynamicRelations() :
 * @property Collection|\App\Documentation\AspectPlugin\Product\ProductEntity[] $products
 */
class Aspect extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    protected $guarded = [];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    protected $dispatchesEvents = [
        'created' => AspectCreated::class,
    ];

    public function path(): BelongsTo
    {
        return $this->belongsTo(Path::class);
    }

    public function parent(): BelongsTo
    {
        return $this
            ->belongsTo(Aspect::class, 'parent_id')
            ;
    }

    /**
     * С помощью этого метода, должны искаться дочерние аспекты (сейчас для просмотра) с path driver eloquent
     * И забираться у него мапперы для меню, что бы сгенерить ссылку
     * Ничего лучше не придумал
     * Хотя вот тут мб стоит уже переехать с логикой в БД?
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this
            ->hasMany(Aspect::class, 'parent_id')
            ;
    }

    public function settings(): HasMany
    {
        return $this->hasMany(AspectConfig::class);
    }

    public function decorators(): HasMany
    {
        return $this->hasMany(Decorator::class);
    }

    public function mappers(): HasMany
    {
        return $this->hasMany(Mapper::class);
    }

    public function validations(): HasMany
    {
        return $this->hasMany(Validation::class);
    }

    public function getPath(): ?Path
    {
        return $this->path;
    }

    public function getMappers(): Collection
    {
        return $this->mappers;
    }

    public function getSettings(): Collection
    {
        return $this->settings;
    }

    public function getSettingsWithCasts(): Collection
    {
        return $this
            ->settings()
            ->withCasts(['value' => AspectConfigValueCast::class])
            ->get()
            ;
    }

    public function getDecorators(): Collection
    {
        return $this->decorators;
    }

    public function getValidations(): Collection
    {
        return $this->validations;
    }
}
