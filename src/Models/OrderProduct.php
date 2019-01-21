<?php

namespace App\Models;

use Framework\AbstractModel;

class OrderProduct extends AbstractModel
{
    private $id;
    private $order;
    private $product;
    private $price;
    private $count;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): OrderProduct
    {
        $this->id = $id;
        return $this;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): OrderProduct
    {
        $this->order = $order;
        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): OrderProduct
    {
        $this->product = $product;
        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice(int $price): OrderProduct
    {
        $this->price = $price;
        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): OrderProduct
    {
        $this->count = $count;
        return $this;
    }

    public function fromArray(array $data): AbstractModel
    {
        $this->setId($data['id']);
        $this->setOrder($data['order']);
        $this->setProduct($data['product']);
        $this->setCount($data['count']);
        $this->setPrice($data['price']);

        return parent::fromArray($data);
    }
}