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

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
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