<?php

namespace Demoshop\Services;

use Demoshop\Repositories\StatisticsRepository;

/**
 * Class StatisticsService
 */
class StatisticsService
{
    /**
     * Get total amount of visiting visitors landing page.
     *
     * @return int
     */
    public static function getTotalHomeViewCount(): int
    {
        $statistic = new StatisticsRepository();
        return $statistic->getStatistics();
    }

    /**
     * Increase home page open count.
     *
     * @param int $count
     * @return void
     */
    public static function increaseHomeViewCount(int $count = 1): void
    {
        $statistic = new StatisticsRepository();
        $statistic->increaseHomeViewCount($count);
    }

}