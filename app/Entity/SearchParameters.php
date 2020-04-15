<?php


namespace Demoshop\Entity;


/**
 * Class SearchParameters
 * @package Demoshop\Entity
 */
class SearchParameters
{
    /**
     * @var string
     */
    private $keyword;
    /**
     * @var float
     */
    private $minPrice;
    /**
     * @var float
     */
    private $maxPrice;
    /**
     * @var int
     */
    private $categoryId;
    /**
     * @var string
     */
    private $search;

    /**
     * SearchParameters constructor.
     *
     * @param string $keyword
     * @param float $minPrice
     * @param float $maxPrice
     * @param int $categoryId
     * @param string $search
     */
    public function __construct(
        string $keyword = '',
        float $minPrice = null,
        float $maxPrice = null,
        int $categoryId = null,
        string $search = ''
    )
    {
        $this->keyword = $keyword;
        $this->minPrice = $minPrice;
        $this->maxPrice = $maxPrice;
        $this->categoryId = $categoryId;
        $this->search = $search;
    }

    /**
     * Get search.
     *
     * @return string
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * Set search.
     *
     * @param string $search
     */
    public function setSearch(string $search): void
    {
        $this->search = $search;
    }

    /**
     * Get keyword.
     *
     * @return string
     */
    public
    function getKeyword(): ?string
    {
        return $this->keyword;
    }

    /**
     * Set keyword.
     *
     * @param string $keyword
     */
    public
    function setKeyword(string $keyword): void
    {
        $this->keyword = $keyword;
    }

    /**
     * Get min price.
     *
     * @return float
     */
    public
    function getMinPrice(): ?float
    {
        return $this->minPrice;
    }

    /**
     * Set min price.
     *
     * @param float $minPrice
     */
    public
    function setMinPrice(float $minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    /**
     * Get max price.
     *
     * @return float
     */
    public
    function getMaxPrice(): ?float
    {
        return $this->maxPrice;
    }

    /**
     * Set max price.
     *
     * @param float $maxPrice
     */
    public
    function setMaxPrice(float $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }

    /**
     * Get category id.
     *
     * @return int
     */
    public
    function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * Set category id.
     *
     * @param int $categoryId
     */
    public
    function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }
}