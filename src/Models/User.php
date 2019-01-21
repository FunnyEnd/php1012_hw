<?php

namespace App\Models;

use Framework\AbstractModel;

class User extends AbstractModel
{
    private $id;
    private $email;
    private $password;
    private $isAdmin;
    private $contactPerson;

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

    public function getIsAdmin(): int
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(int $isAdmin): User
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    public function getContactPerson(): ContactPerson
    {
        return $this->contactPerson;
    }

    public function setContactPerson(ContactPerson $contactPerson): User
    {
        $this->contactPerson = $contactPerson;
        return $this;
    }

    public function fromArray(array $data): AbstractModel
    {
        $this->setId($data['id']);
        $this->setEmail($data['email']);
        $this->setPassword($data['password']);
        $this->setContactPerson($data['contact_person']);
        $this->setIsAdmin($data['is_admin']);

        return parent::fromArray($data);
    }

}