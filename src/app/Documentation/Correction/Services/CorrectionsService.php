<?php

namespace App\Documentation\Correction\Services;

use App\Config\DTO\ConfigDTO;
use App\Documentation\Correction\Contracts\CorrectionsServiceInterface;
use App\Documentation\Correction\DTO\CorrectionDTO;
use App\Documentation\Correction\Entities\Correction;
use App\Documentation\Correction\Repositories\CorrectionRepository;
use App\EloquentBuilderFilter;
use App\User\Entities\User;
use Carbon\Carbon;

final readonly class CorrectionsService implements CorrectionsServiceInterface
{
    public function __construct(
        private ConfigDTO $config,
        private CorrectionRepository $correctionRepository,
    ) {
    }

    public function store(User $user, CorrectionDTO $correction): void
    {
        $entity = new Correction();
        $entity->edit_date = new Carbon();
        $entity->user_crm_id = $user->crmId;
        $entity->user_name = $user->nickName;
        $entity->is_approved = $this->isApproved($user);
        $entity->is_merged = false;
        $entity->is_archived = false;
        $entity->release_name = $correction->releaseName;
        $entity->page_url = $correction->pageUrl;
        $entity->page_xpath = $correction->pageXpath;
        $entity->html_eng = $correction->contentEng;
        $entity->html_rus_old = $correction->contentRusOld;
        $entity->html_rus_new = $correction->contentRusNew;

        $this->correctionRepository->save($entity);
    }

    public function getApprovedCorrections(string $releaseUrl): array
    {
        $approvedCorrections = $this->correctionRepository->findAllByUserIdAndPageURLAndIsApproved(
            // TODO пробросить пользака
            1,
            $releaseUrl
        );

        return $approvedCorrections
            ->map(static fn (Correction $correction) => [
                $correction->page_xpath,
                $correction->html_rus_old,
                $correction->html_rus_new,
            ])
            ->toArray();
    }

    private function isApproved(User $user): bool
    {
        $approvedCorrections = $this->correctionRepository->getCountByUserIdAndIsApproved($user->crmId);
        $minCorrectionsToAutoApprove = (int) $this->config->countForPremoderation;

        return $approvedCorrections > $minCorrectionsToAutoApprove;
    }
}
