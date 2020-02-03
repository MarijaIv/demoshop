<?php


namespace Demoshop\Model;


class Admin
{
    private $id;
    private $username;
    private $password;

    // get admin id
    public function getId(): int
    {
        return $this->id;
    }

    // get admin username
    public function getUsername(): string
    {
        return $this->username;
    }

    //get admin password
    public function getPassword(): string
    {
        return $this->password;
    }
}