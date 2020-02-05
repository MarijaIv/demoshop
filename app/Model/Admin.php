<?php


namespace Demoshop\Model;

/**
 * Class Admin
 * @package Demoshop\Model
 */
class Admin
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $username;
    /**
     * @var
     */
    private $password;

    /**
     * Get admin id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get admin username.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Get admin password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}