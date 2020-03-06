<?php

namespace Demoshop\DTO;

use Demoshop\Model\Category;
use Illuminate\Database\Eloquent\Model;

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
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
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
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
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