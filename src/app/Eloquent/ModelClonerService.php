<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\MorphedByMany;

class ModelClonerService
{
    public function clone(Model $model, array $relationships): Model
    {
        return DB::transaction(function () use ($model, $relationships) {
            $newModel = $model->replicate();
            $newModel->push();

            foreach ($relationships as $relation) {
                $this->cloneRelationship($model, $newModel, $relation);
            }

            return $newModel->refresh();
        });
    }

    protected function cloneRelationship(Model $original, Model $clone, string $relation): void
    {
        if (!method_exists($original, $relation)) {
            return;
        }

        $relationship = $original->{$relation}();

        match (true) {
            $relationship instanceof HasOne => $this->cloneHasOne($relationship, $clone),
            $relationship instanceof HasMany => $this->cloneHasMany($relationship, $clone),
            $relationship instanceof BelongsToMany => $this->cloneBelongsToMany($relationship, $clone),
            $relationship instanceof MorphOne => $this->cloneMorphOne($relationship, $clone),
            $relationship instanceof MorphMany => $this->cloneMorphMany($relationship, $clone),
            $relationship instanceof MorphToMany => $this->cloneMorphToMany($relationship, $clone),
            $relationship instanceof MorphedByMany => $this->cloneMorphedByMany($relationship, $clone),
            default => null
        };
    }

    protected function cloneHasOne(HasOne $relationship, Model $clone): void
    {
        if ($related = $relationship->first()) {
            $clone->{$relationship->getRelationName()}()->save($related->replicate());
        }
    }

    // HasMany
    protected function cloneHasMany(HasMany $relationship, Model $clone): void
    {
        foreach ($relationship->get() as $related) {
            $clone->{$relationship->getRelationName()}()->save($related->replicate());
        }
    }

    protected function cloneBelongsToMany(BelongsToMany $relationship, Model $clone): void
    {
        $clone->{$relationship->getRelationName()}()->sync(
            $relationship->pluck($relationship->getRelated()->getKeyName())
        );
    }

    protected function cloneMorphOne(MorphOne $relationship, Model $clone): void
    {
        if ($related = $relationship->first()) {
            $newRelated = $related->replicate();
            $newRelated->{$relationship->getMorphType()} = get_class($clone);
            $clone->{$relationship->getRelationName()}()->save($newRelated);
        }
    }

    protected function cloneMorphMany(MorphMany $relationship, Model $clone): void
    {
        foreach ($relationship->get() as $related) {
            $newRelated = $related->replicate();
            $newRelated->{$relationship->getMorphType()} = get_class($clone);
            $newRelated->{$relationship->getForeignKeyName()} = $clone->getKey();
            $newRelated->save();
        }
    }

    protected function cloneMorphToMany(MorphToMany $relationship, Model $clone): void
    {
        $clone->{$relationship->getRelationName()}()->sync(
            $relationship->pluck($relationship->getRelated()->getKeyName())
        );
    }

    protected function cloneMorphedByMany(MorphedByMany $relationship, Model $clone): void
    {
        $this->cloneMorphToMany($relationship, $clone);
    }
}
