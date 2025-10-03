<?php

namespace App\Documentation\Aspect\Casts;

use App\Documentation\Aspect\DTO\AspectConfigDTO;
use App\Documentation\Aspect\Entities\AspectConfig;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class AspectConfigValueCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $this->modifySomeRowsForGet($model, $key, $value, $attributes);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $this->modifySomeRowsForSet($model, $key, $value, $attributes);
    }

    protected function modifySomeRowsForGet(AspectConfig $model, string $key, mixed $value, array $attributes): mixed
    {
        return match ($model->name) {
            AspectConfigDTO::IS_WIKI_ASPECT => (bool) $value,
            AspectConfigDTO::IS_WORK_DIRECTLY => (bool) $value,
            default => $value,
        };
    }

    protected function modifySomeRowsForSet(AspectConfig $model, string $key, mixed $value, array $attributes): mixed
    {
        return match ($model->name) {
            default => $value,
        };
    }
}
