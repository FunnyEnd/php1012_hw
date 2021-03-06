<?php

namespace App\Models;

use Framework\AbstractModel;

class Basket extends AbstractModel
{
    private $id;
    private $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Basket
    {
        $this->id = $id;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Basket
    {
        $this->user = $user;
        return $this;
    }

    public function fromArray(array $data): AbstractModel
    {
        $this->setId($data['id']);
        $this->setUser($data['user']);

        return parent::fromArray($data);
    }

}