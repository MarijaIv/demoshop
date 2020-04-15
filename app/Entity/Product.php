<?php

namespace Demoshop\Entity;

/**
 * Class Product
 * @package Demoshop\Entity
 */
class Product
{
    /**
     * @var int
     */
    private $categoryId;
    /**
     * @var string
     */
    private $sku;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $brand;
    /**
     * @var float
     */
    private $price;
    /**
     * @var string
     */
    private $shortDescription;
    /**
     * @var string
     */
    private $description;
    /**
     * @var bool
     */
    private $enabled;
    /**
     * @var bool
     */
    private $featured;
    /**
     * @var string
     */
    private $image;

    /**
     * Product constructor.
     *
     * @param int $categoryId
     * @param string $sku
     * @param string $title
     * @param string $brand
     * @param float $price
     * @param string $shortDescription
     * @param string $description
     * @param bool $enabled
     * @param bool $featured
     * @param string $image
     */
    public function __construct(
        int $categoryId,
        string $sku,
        string $title,
        string $brand,
        float $price,
        string $shortDescription,
        string $description,
        bool $enabled,
        bool $featured,
        string $image = ''
    )
    {
        $this->categoryId = $categoryId;
        $this->sku = $sku;
        $this->title = $title;
        $this->brand = $brand;
        $this->price = $price;
        $this->shortDescription = $shortDescription;
        $this->description = $description;
        $this->enabled = $enabled;
        $this->featured = $featured;
        $this->image = $image;
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * Get parent category id.
     *
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * Set parent category id.
     *
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * Get sku.
     *
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Set sku.
     *
     * @param string $sku
     */
    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Get brand.
     *
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * Set brand.
     *
     * @param string $brand
     */
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Set price.
     *
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * Get short description.
     *
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    /**
     * Set short description.
     *
     * @param string $shortDescription
     */
    public function setShortDescription(string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Is product enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Set enabled.
     *
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * Is product featured.
     *
     * @return bool
     */
    public function isFeatured(): bool
    {
        return $this->featured;
    }

    /**
     * Set featured.
     *
     * @param bool $featured
     */
    public function setFeatured(bool $featured): void
    {
        $this->featured = $featured;
    }
}