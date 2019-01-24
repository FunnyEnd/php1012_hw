<?php

namespace App\Models;

use Framework\AbstractModel;

class Order extends AbstractModel
{
    protected $id;
    protected $user;
    protected $confirm;
    protected $comment;
    protected $contactPerson;
    protected $price;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Order
    {
        $this->id = $id;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Order
    {
        $this->user = $user;
        return $this;
    }

    public function getConfirm(): int
    {
        return $this->confirm;
    }

    public function getConfirmAsString(): string
    {
        if ($this->confirm == 0) {
            return 'Not confirmed';
        } else {
            return 'Confirm';
        }
    }

    public function setConfirm(int $confirm): Order
    {
        $this->confirm = $confirm;
        return $this;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): Order
    {
        $this->comment = $comment;
        return $this;
    }

    public function getContactPerson(): ContactPerson
    {
        return $this->contactPerson;
    }

    public function setContactPerson(ContactPerson $contactPerson): Order
    {
        $this->contactPerson = $contactPerson;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): Order
    {
        $this->price = $price;
        return $this;
    }


    public function fromArray(array $data): AbstractModel
    {
        $this->setId($data['id']);
        $this->setUser($data['user']);
        $this->setConfirm($data['confirm']);
        $this->setComment($data['comment']);
        $this->setContactPerson($data['contact_person']);
        $this->setPrice($data['price']);

        return parent::fromArray($data);
    }
}