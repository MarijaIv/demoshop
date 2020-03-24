<?php


namespace Demoshop\DTO;


use Illuminate\Database\Eloquent\Model;

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
     * @param Model $data
     */
    public function __construct(Model $data)
    {
        $this->id = $data['id'];
        $this->categoryId = $data['category_id'];
        $this->sku = $data['sku'];
        $this->title = $data['title'];
        $this->brand = $data['brand'];
        $this->price = $data['price'];
        $this->shortDescription = $data['short_description'];
        $this->description = $data['description'];
        $this->image = $data['image'];
        $this->enabled = $data['enabled'];
        $this->featured = $data['featured'];
        $this->viewCount = $data['viewCount'];
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
     * Get image.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Set image.
     *
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
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

    /**
     * Get view count.
     *
     * @return int
     */
    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    /**
     * Set view count.
     *
     * @param int $viewCount
     */
    public function setViewCount(int $viewCount): void
    {
        $this->viewCount = $viewCount;
    }

    /**
     * Format product.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'title' => $this->title,
            'brand' => $this->brand,
            'category' => $this->categoryId,
            'price' => $this->price,
            'shortDescription' => $this->shortDescription,
            'description' => $this->description,
            'enabled' => $this->enabled,
            'featured' => $this->featured,
            'image' => $this->image,
        ];
    }
}