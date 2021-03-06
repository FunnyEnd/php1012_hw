<?php

namespace App\Models;

use Framework\AbstractModel;

class BasketProduct extends AbstractModel
{
    protected $id;
    protected $basket;
    protected $product;
    protected $count;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): BasketProduct
    {
        $this->id = $id;
        return $this;
    }

    public function getBasket(): Basket
    {
        return $this->basket;
    }

    public function setBasket(Basket $basket): BasketProduct
    {
        $this->basket = $basket;
        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): BasketProduct
    {
        $this->product = $product;
        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): BasketProduct
    {
        $this->count = $count;
        return $this;
    }

    public function getPriceAtBills(): float
    {
        return ($this->getProduct()->getPriceAtCoins() * $this->getCount()) / 100;
    }

    public function getPriceAtCoins(): float
    {
        return $this->getProduct()->getPriceAtCoins() * $this->getCount();
    }

    public function fromArray(array $data): AbstractModel
    {
        $this->setId($data['id']);
        $this->setBasket($data['basket']);
        $this->setProduct($data['product']);
        $this->setCount($data['count']);

        return parent::fromArray($data);
    }

}