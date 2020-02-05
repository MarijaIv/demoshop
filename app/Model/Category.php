<?php


namespace Demoshop\Model;

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Class Category
 * @package Demoshop\Model
 */
class Category
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $parent_id;
    /**
     * @var
     */
    private $code;
    /**
     * @var
     */
    private $title;
    /**
     * @var
     */
    private $description;

    /**
     * Get category id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get category parent id.
     *
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parent_id;
    }

    /**
     * Get category code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get category title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get category description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get total amount of categories.
     *
     * @return void
     */
    public static function getAmountOfCategories(): void
    {
       echo Capsule::table('category')->count();
    }

}