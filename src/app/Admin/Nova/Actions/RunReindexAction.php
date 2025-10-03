<?php

namespace App\Admin\Nova\Actions;

use App\Documentation\AspectPlugin\Product\ProductEntity;
use App\Documentation\Uploader\Events\ProductUploaded;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ramsey\Uuid\Uuid;

class RunReindexAction extends Action
{
//    use InteractsWithQueue, Queueable;

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

        /** @var ProductEntity $model */
        $model = $models->first();
        $aspect = $model->getAspect();

        app(Dispatcher::class)
            ->dispatch(new ProductUploaded(
                aspectId: $aspect->id,
                jobUuid: Uuid::uuid4()->toString(),
                productPath: $model->root_path . $model->root_folder,
            ));

        return Action::message('RunReindexAction: IS RUN');
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
