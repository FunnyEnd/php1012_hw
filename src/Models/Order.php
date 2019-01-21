<?php

namespace App\Models;

use Framework\AbstractModel;

class Order extends AbstractModel
{
    private $id;
    private $user;
    private $confirm;
    private $comment;
    private $contactPerson;


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

    public function fromArray(array $data): AbstractModel
    {
        $this->setId($data['id']);
        $this->setUser($data['user']);
        $this->setConfirm($data['confirm']);
        $this->setComment($data['comment']);
        $this->setContactPerson($data['contact_person']);

        return parent::fromArray($data);
    }
}