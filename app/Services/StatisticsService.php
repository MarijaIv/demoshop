<?php

namespace Demoshop\Services;

use Demoshop\Repositories\StatisticsRepository;
use Demoshop\ServiceRegistry\ServiceRegistry;

/**
 * Class StatisticsService
 */
class StatisticsService
{
    /**
     * @var StatisticsRepository
     */
    private $statisticsRepository;

    /**
     * StatisticsService constructor.
     * @param StatisticsRepository $statisticsRepository
     */
    public function __construct(StatisticsRepository $statisticsRepository)
    {
        $this->statisticsRepository = $statisticsRepository;
    }


    /**
     * Get total amount of visiting visitors landing page.
     *
     * @return int
     */
    public function getTotalHomeViewCount(): int
    {
        return $this->statisticsRepository->getStatistics();
    }

    /**
     * Increase home page open count.
     *
     * @param int $count
     * @return void
     */
    public function increaseHomeViewCount(int $count = 1): void
    {
        $this->statisticsRepository->increaseHomeViewCount($count);
    }

}