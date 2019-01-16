<?php
/**
 * Created by PhpStorm.
 * User: FoFF
 * Date: 16.01.2019
 * Time: 23:55
 */

namespace App\Models;


use Framework\BaseModel;

class OrderProduct extends BaseModel
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

    /**
     * Convert array to OrderProduct
     * @param array $data
     */
    public function fromArray(array $data): void
    {
        $this->setId($data['id']);
        $this->setOrder($data['order']);
        $this->setProduct($data['product']);
        $this->setCount($data['count']);
        $this->setPrice($data['price']);
        $this->setCreateAt($data['create_at']);
        $this->setUpdateAt($data['update_at']);
    }
}