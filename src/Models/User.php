<?php

namespace App\Models;

use Framework\BaseModel;

class User extends BaseModel
{
    private $id;
    private $email;
    private $password;
    private $firstName;
    private $lastName;
    private $isAdmin;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getIsAdmin(): int
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(int $isAdmin): User
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * Convert array to User
     * @param array $data
     */
    public function fromArray(array $data): void
    {
        $this->setId($data['id']);
        $this->setEmail($data['email']);
        $this->setPassword($data['password']);
        $this->setFirstName($data['first_name']);
        $this->setLastName($data['last_name']);
        $this->setIsAdmin($data['is_admin']);
        $this->setCreateAt($data['create_at']);
        $this->setUpdateAt($data['update_at']);
    }

}