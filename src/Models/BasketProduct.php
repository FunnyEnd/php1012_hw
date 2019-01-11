<?php

namespace App\Models;

use Framework\BaseModel;

class BasketProduct extends BaseModel
{
    private $id;
    private $basket;
    private $product;
    private $count;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getBasket(): Basket
    {
        return $this->basket;
    }

    public function setBasket(Basket $basket): void
    {
        $this->basket = $basket;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * Convert array to BasketProduct
     * @param array $data
     */
    public function fromArray(array $data): void
    {
        $this->setId($data['id']);
        $this->setBasket($data['basket']);
        $this->setProduct($data['product']);
        $this->setCount($data['count']);
        $this->setCreateAt($data['create_at']);
        $this->setUpdateAt($data['update_at']);
    }

}