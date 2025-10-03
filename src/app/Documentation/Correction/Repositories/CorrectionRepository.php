<?php

namespace App\Documentation\Correction\Repositories;

use App\Documentation\Correction\Entities\Correction;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pipeline\Pipeline;

final readonly class CorrectionRepository
{
    public function __construct(
        private Container       $app,
        private DatabaseManager $db,
    )
    {
    }

    public function findAll(): iterable
    {
        return $this->getBuilder()->get()->toArray();
    }

    public function findAllByFilter(array $filters): iterable
    {
        return $this
            ->getPipeline($filters)
            ->then(function (Builder $builder) {
                return $builder->get();
            });
    }

    /**
     * @param int $userId
     * @param string $url
     * @return Collection<Correction>
     */
    public function findAllByUserIdAndPageURLAndIsApproved(int $userId, string $url): Collection
    {
        return $this
            ->getBuilder()
            ->where('page_url', $url)
            ->where('is_merged', 0)
            ->where(function (Builder $builder) use ($userId) {
                $builder
                    ->where('is_approved', 1)
                    ->where('user_crm_id', $userId);
            })
            ->get()
            ;
    }

    public function getCountByUserIdAndIsApproved(int $userId): int
    {
        return $this
            ->getBuilder()
            ->where('user_crm_id', $userId)
            ->where('is_approved', 1)
            ->count()
            ;
    }

    public function getCountByFilter(array $filters): int
    {
        return $this
            ->getPipeline($filters)
            ->then(function (Builder $builder) {
                return $builder->count();
            });
    }

    public function getStatistics(): array
    {
        $sql = <<<SQL
SELECT
    user_name,
    user_crm_id as user_id,
    count(*) AS all_edits,
    count(if(is_approved=1,1,null)) AS approved_yes,
    round(count(if(is_approved=1,1,null)) / count(*) * 100, 2) AS approved_yes_pc,
    count(if(is_approved=1,null,1)) AS approved_no,
    round(count(if(is_approved=1,null,1)) / count(*) * 100, 2) AS approved_no_pc,
    count(distinct page_url) AS edit_pages
FROM `corrections`
GROUP BY user_name, user_crm_id
ORDER BY all_edits DESC
LIMIT 50
SQL;
        return $this->db->select($sql);
    }

    public function save(Correction $entity): void
    {
        $entity->save();
    }

    private function getPipeline(array $pipes): Pipeline
    {
        return (new Pipeline($this->app))
            ->send($this->getBuilder())
            ->pipe($pipes);
    }

    private function getBuilder(): Builder
    {
        return (new Correction())->newQuery();
    }
}
