<?php

namespace App\Models;

use Framework\BaseModel;

class Basket extends BaseModel
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

    /**
     * Convert array to Basket
     * @param array $data
     */
    public function fromArray(array $data): void
    {
        $this->setId($data['id']);
        $this->setUser($data['user']);
        $this->setCreateAt($data['create_at']);
        $this->setUpdateAt($data['update_at']);
    }

}