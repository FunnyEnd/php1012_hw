<?php

namespace App\Models;

use Framework\AbstractModel;

class ContactPerson extends AbstractModel
{
    protected $id;
    protected $first_name;
    protected $last_name;
    protected $phone;
    protected $city;
    protected $stock;
    protected $email;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): ContactPerson
    {
        $this->id = $id;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): ContactPerson
    {
        $this->first_name = $first_name;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): ContactPerson
    {
        $this->last_name = $last_name;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): ContactPerson
    {
        $this->phone = $phone;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): ContactPerson
    {
        $this->city = $city;
        return $this;
    }

    public function getStock(): string
    {
        return $this->stock;
    }

    public function setStock(string $stock): ContactPerson
    {
        $this->stock = $stock;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): ContactPerson
    {
        $this->email = $email;
        return $this;
    }

    public function fromArray(array $data): AbstractModel
    {
        $this->setId($data['id']);
        $this->setFirstName($data['first_name']);
        $this->setLastName($data['last_name']);
        $this->setPhone($data['phone']);
        $this->setCity($data['city']);
        $this->setStock($data['stock']);
        $this->setEmail($data['email']);

        return parent::fromArray($data);
    }
}