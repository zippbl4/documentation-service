<?php

namespace App\Correction;

use App\Documentation\Correction\Repositories\CorrectionRepository;
use Laravel\Nova\Card;

class StatisticCard extends Card
{
    public function __construct(
        private readonly CorrectionRepository $repository,
        $component = null,
    ) {
        parent::__construct($component);
    }

    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'correction-statistic-cart';
    }

    public function withStatistic(): self
    {
        $this->withMeta([
            'statistic' => $this->repository->getStatistics()
        ]);

        return $this;
    }
}
