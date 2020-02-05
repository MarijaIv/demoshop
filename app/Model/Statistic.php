<?php


namespace Demoshop\Model;

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Class Statistic
 * @package Demoshop\Model
 */
class Statistic
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $home_view_count;

    /**
     * Get statistic id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get statistic home view count.
     *
     * @return int
     */
    public function getHomeViewCount(): int
    {
        return $this->home_view_count;
    }

    /**
     * Get total amount of visiting visitors landing page.
     *
     * @return void
     */
    public static function getTotalHomeViewCount(): void
    {
        $result = Capsule::table('statistics')->select('home_view_count')->where('id', '=' ,1)->get();
        echo $result[0]->home_view_count;
    }

}