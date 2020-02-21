<?php


namespace Demoshop\Repositories;


use Demoshop\Model\Statistic;

/**
 * Class StatisticsRepository
 * @package Demoshop\Repositories
 */
class StatisticsRepository
{
    /**
     * Get total amount of visiting visitors landing page.
     *
     * @return int
     */
    public function getStatistics(): int
    {
        return Statistic::query()->where('id', '=', 1)->first()->home_view_count;
    }

    /**
     * Increase home page open count.
     *
     * @param int $count
     * @return void
     */
    public function increaseHomeViewCount(int $count): void
    {
        $result = Statistic::query()->where('id', '=', 1)->first();
        if ($result) {
            $result->home_view_count += $count;
            $result->save();
        }
    }
}