<?php


namespace Demoshop\Entity;


/**
 * Class Category
 * @package Demoshop\Entity
 */
class Category
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $parentId;
    /**
     * @var string
     */
    private $code;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $description;

    /**
     * Category constructor.
     *
     * @param int $id
     * @param int $parentId
     * @param string $code
     * @param string $title
     * @param string $description
     */
    public function __construct(
        string $code = '',
        string $title = '',
        string $description = '',
        int $parentId = null,
        int $id = null
    )
    {
        $this->id = $id;
        $this->parentId = $parentId;
        $this->code = $code;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
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
     * Get parent id.
     *
     * @return int
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * Set parent id.
     *
     * @param int $parentId
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Set code.
     *
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle(): ?string
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
     * Get description.
     *
     * @return string
     */
    public function getDescription(): ?string
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
}