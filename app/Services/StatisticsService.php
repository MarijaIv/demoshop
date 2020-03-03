<?php

namespace Demoshop\Services;

use Demoshop\ServiceRegistry\ServiceRegistry;

/**
 * Class StatisticsService
 */
class StatisticsService
{
    public $statisticsRepository;

    /**
     * StatisticsService constructor.
     */
    public function __construct()
    {
        $this->statisticsRepository = ServiceRegistry::get('StatisticsRepository');
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