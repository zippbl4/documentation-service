<?php

namespace App\Admin\Nova\Actions;

use App\Eloquent\ModelClonerService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class CloneAspect extends Action
{
//    use InteractsWithQueue, Queueable;

    public $name = 'Клонировать аспект';

    public $confirmText = 'Запустить клонирование? Клонированный аспект будет выключен по умолчанию.';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() > 1) {
            return Action::danger('Please run this on only one user resource.');
        }

        /** @var Model $current */
        $current = $models->first();
        $relationships = $fields->relationships ?? [];
        $new = app(ModelClonerService::class)->clone($current, $relationships);
        return Action::message(sprintf('Cloned: %s. New: %s', $current->id, $new->id));
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
