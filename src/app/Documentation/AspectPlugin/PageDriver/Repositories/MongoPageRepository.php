<?php

namespace App\Documentation\AspectPlugin\PageDriver\Repositories;

use Advoor\NovaEditorJs\NovaEditorJsCast;
use App\Config\DTO\ConfigDTO;
use App\Config\Enums\FeatureFlagEnum;
use App\Documentation\AspectPlugin\PageDriver\Entities\MongoPage;

class MongoPageRepository implements PageRepositoryInterface
{
    public function __construct(private ConfigDTO $config)
    {
    }

    public function findByFilter(array $filter): ?string
    {
        $page = $this
            ->getBuilder()
        ;

        foreach ($filter as $key => $value) {
            $page = $page->where($key, $value);
        }

        return $page->first()?->content;
    }

    public function create(array $uniquenessCondition, array $additionalData)
    {
        // TODO: Implement create() method.
    }

    /**
     * Получение всех корневых страниц без связей
     *
     * @return void
     */
    public function findRootPages()
    {
        return MongoPage::query()
            ->whereNull('parent_id')
            ->with('children')
            ->get();
    }

    /**
     * Получение всех корневых с непосредственными детьми
     *
     * @return void
     */
    public function findRootPagesWithImmediateChildren()
    {

    }

    /**
     * Полное дерево
     *
     * @return void
     */
    public function findFullTree()
    {

    }

    /**
     * Получение страницы с непосредственными детьми
     *
     * @return void
     */
    public function findPageWithImmediateChildren()
    {

    }

    /**
     * Получение страницы со всеми детьми
     *
     * @return void
     */
    public function findPageWithAllChildren()
    {

    }

    /**
     * Получение страницы с детьми, родителем и братьями
     *
     * @return void
     */
    public function findWithFamily(string|int $id)
    {
    }

    /**
     * Получение страницы с детьми, всеми родителями и братьями
     *
     * @return void
     */
    public function findPageWithFullFamily()
    {

    }

    private function getBuilder()
    {
        return (new MongoPage())->newQuery();
    }
}
