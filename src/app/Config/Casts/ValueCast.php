<?php

namespace App\Config\Casts;

use App\Config\Entities\Settings;
use App\Config\Enums\ModificationRow;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ValueCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $this->modifySomeRowsForGet($model, $key, $value, $attributes);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $this->modifySomeRowsForSet($model, $key, $value, $attributes);
    }

    protected function modifySomeRowsForGet(Settings $model, string $key, mixed $value, array $attributes): mixed
    {
        return match ($model->name) {
            ModificationRow::LIST_OF_ASPECTS_WORK_DIRECTLY => json_decode($value, true),
            ModificationRow::LIST_OF_WIKI_ASPECTS => json_decode($value, true),
            default => $value,
        };
    }

    protected function modifySomeRowsForSet(Settings $model, string $key, mixed $value, array $attributes): mixed
    {
        return match ($model->name) {
            default => $value,
        };
    }
}
