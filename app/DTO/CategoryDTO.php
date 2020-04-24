<?php

namespace Demoshop\DTO;

use Demoshop\Model\Category;

/**
 * Class CategoryDTO
 * @package Demoshop\DTO
 */
class CategoryDTO
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $parentId;
    /**
     * @var string
     */
    public $code;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $description;
    /**
     * @var array
     */
    public $children;

    /**
     * CategoryDTO constructor.
     * @param Category $data
     */
    public function __construct(Category $data)
    {
        $this->id = $data['id'];
        $this->parentId = $data['parent_id'];
        $this->code = $data['code'];
        $this->title = $data['title'];
        $this->description = $data['description'];
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
     * Get parent id.
     *
     * @return int
     */
    public function getParentId(): int
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
    public function getCode(): string
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
     * Get children.
     *
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * Set children.
     *
     * @param array $children
     */
    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    /**
     * Format category.
     *
     * @return array
     */
    public function toArray(): array
    {
        $formattedChildren = [];

        foreach ($this->children as $child) {
            $formattedChildren[] = $child->toArray();
        }

        return [
            'id' => $this->id,
            'parentId' => $this->parentId,
            'code' => $this->code,
            'title' => $this->title,
            'description' => $this->description,
            'children' => $formattedChildren,
        ];
    }

    /**
     * Add child to children array.
     *
     * @param Category $child
     */
    public function addChild(Category $child): void
    {
        $newChild = new CategoryDTO($child);
        $this->children[] = $newChild;
    }
}