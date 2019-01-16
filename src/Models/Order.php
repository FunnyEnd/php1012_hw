<?php

namespace App\Models;

use Framework\BaseModel;

class Order extends BaseModel
{
    protected $id;
    protected $user;
    protected $confirm;
    protected $comment;
    protected $userStock;
    protected $userCity;
    protected $userPhone;
    protected $userFirstName;
    protected $userLastName;

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

    public function getUserStock(): string
    {
        return $this->userStock;
    }

    public function setUserStock(string $userStock): Order
    {
        $this->userStock = $userStock;
        return $this;
    }

    public function getUserCity(): string
    {
        return $this->userCity;
    }

    public function setUserCity(string $userCity): Order
    {
        $this->userCity = $userCity;
        return $this;
    }

    public function getUserPhone()
    {
        return $this->userPhone;
    }

    public function setUserPhone(string $userPhone): Order
    {
        $this->userPhone = $userPhone;
        return $this;
    }

    public function getUserFirstName(): string
    {
        return $this->userFirstName;
    }

    public function setUserFirstName(string $userFirstName): Order
    {
        $this->userFirstName = $userFirstName;
        return $this;
    }

    public function getUserLastName(): string
    {
        return $this->userLastName;
    }

    public function setUserLastName(string $userLastName): Order
    {
        $this->userLastName = $userLastName;
        return $this;
    }

    /**
     * Convert array to Order
     * @param array $data
     */
    public function fromArray(array $data): void
    {
        $this->setId($data['id']);
        $this->setUser($data['user']);
        $this->setConfirm($data['confirm']);
        $this->setComment($data['comment']);
        $this->setUserStock($data['user_stock']);
        $this->setUserCity($data['user_city']);
        $this->setUserPhone($data['user_phone']);
        $this->setUserFirstName($data['user_first_name']);
        $this->setUserLastName($data['user_last_name']);
        $this->setCreateAt($data['create_at']);
        $this->setUpdateAt($data['update_at']);
    }
}