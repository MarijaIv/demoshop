<?php


namespace Demoshop\DTO;


/**
 * Class ProductDTO
 * @package Demoshop\DTO
 */
class ProductDTO
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $categoryId;
    /**
     * @var string
     */
    public $sku;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $brand;
    /**
     * @var float
     */
    public $price;
    /**
     * @var string
     */
    public $shortDescription;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string
     */
    public $image;
    /**
     * @var bool
     */
    public $enabled;
    /**
     * @var bool
     */
    public $featured;
    /**
     * @var int
     */
    public $viewCount;

    /**
     * ProductDTO constructor.
     * @param object $data
     */
    public function __construct(object $data)
    {
        $this->id = $data['id'];
        $this->categoryId = $data['category_id'];
        $this->sku = $data['sku'];
        $this->title = $data['title'];
        $this->brand = $data['brand'];
        $this->price = $data['price'];
        $this->shortDescription = $data['short_description'];
        $this->description = $data['description'];
        $this->image = base64_encode($data['image']);
        $this->enabled = $data['enabled'];
        $this->featured = $data['featured'];
        $this->viewCount = $data['viewCount'];
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    /**
     * @param string $shortDescription
     */
    public function setShortDescription(string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public function isFeatured(): bool
    {
        return $this->featured;
    }

    /**
     * @param bool $featured
     */
    public function setFeatured(bool $featured): void
    {
        $this->featured = $featured;
    }

    /**
     * @return int
     */
    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    /**
     * @param int $viewCount
     */
    public function setViewCount(int $viewCount): void
    {
        $this->viewCount = $viewCount;
    }
}