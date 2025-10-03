<?php

namespace App\Documentation\AspectPlugin\PageDriver\Entities;

use App\Dictionary\ProductDictionary;
use App\Eloquent\ExcludeColumnsScopeTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;
use MongoDB\Laravel\Relations\HasMany;

/**
 * @property int $id
 * @property int $aspect_id
 * @property string $lang - rus
 * @property string $product - matlab
 * @property string $version - R2018b
 * @property string $part - matlab_external
 * @property string $page - matlab_external/work-with-members-of-a-net-enumeration.html
 * @property string $title - Работа с участниками перечисления.NET
 * @property string $content - после обработки \App\ContentEngine\Contracts\ReadabilityInterface::parse
 */
class MongoPage extends Model
{
    use ExcludeColumnsScopeTrait;

    protected $connection = 'mongodb';
    protected $table = 'pages';
    protected $guarded = [];

    /**
     * @see https://github.com/mongodb/laravel-mongodb/issues/1902
     *
     * @param $value
     * @return mixed|null
     */
    public function getIdAttribute($value = null)
    {
        return $value;
    }

    public function isWiki(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $this->product === ProductDictionary::WIKI,
        );
    }

    public function parent(): BelongsTo
    {
        return $this
            ->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this
            ->hasMany(self::class, 'parent_id');
    }

    /**
     * Предки
     */
    public function ancestors(): BelongsTo
    {
        return $this->parent()->with('ancestors');
    }

    public function getAllAncestors(): Collection
    {
        $ancestors = collect();
        $parent = $this->parent;

        while ($parent) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    /**
     * Потомки
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    public function getAllDescendants(): Collection
    {
        $descendants = collect();

        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getAllDescendants());
        }

        return $descendants;
    }

    /**
     * Соседи
     */
    public function siblings(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', '_id')
            ->where('_id', '!=', $this->_id);
    }

    public function hasChild(): bool
    {
        return $this->children_count > 0 ?? $this->children->count() > 0;
    }

    public function hasDescendants(): bool
    {
        return $this->descendants->count() > 0;
    }

    public function isChildLoaded(): bool
    {
        return $this->relationLoaded('children');
    }

    public function getRoute(): string
    {
        return route('wiki.show.page', [
            'lang' => $this->lang,
            'page' => $this->page,
        ]);
    }
}
