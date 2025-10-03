<?php

namespace App\Documentation\AspectPlugin\PageDriver\Repositories;

use Advoor\NovaEditorJs\NovaEditorJsCast;
use App\Config\DTO\ConfigDTO;
use App\Config\Enums\FeatureFlagEnum;
use App\Documentation\AspectPlugin\PageDriver\Entities\MysqlPage;
use Illuminate\Database\Eloquent\Builder;

class MysqlPageRepository implements PageRepositoryInterface
{
    public function __construct(private ConfigDTO $config)
    {
    }

    public function findByFilter(array $filter)
    {
        $page = $this
            ->getBuilder()
            ->when(
                $this->config->editorJsFeatureFlag === FeatureFlagEnum::Enabled,
                fn (Builder $builder) => $builder->withCasts(['content' => NovaEditorJsCast::class,]),
            )
        ;

        foreach ($filter as $key => $value) {
            $page = $page->where($key, $value);
        }

        return $page->first();
    }

    public function create(array $uniquenessCondition, array $additionalData)
    {
        // TODO: Implement create() method.
    }

    /**
     * @param array $values
     * @return void
     */
    public function updateOrCreateMany(array $values): void
    {
        $this
            ->getBuilder()
            ->upsert(
                // Values to insert or update
                $values,
                // Column(s) that uniquely identify records within the associated table
                [
                    'lang', 'product', 'version', 'page',
                ],
                // Array of columns that should be updated if a matching record already exists in the database
                [
                    'title', 'content',
                ]
            );
    }

    private function getBuilder(): Builder
    {
        return (new MysqlPage())->newQuery();
    }
}
