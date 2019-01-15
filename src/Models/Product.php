<?php

namespace App\Models;

use Framework\BaseModel;

class Product extends BaseModel
{
    private $id;
    private $title;
    private $description;
    private $availability;
    private $category;
    private $price;
    private $image;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Product
    {
       $this->id = $id;
       return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Product
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Product
    {
        $this->description = $description;
        return $this;
    }

    public function getAvailability(): int
    {
        return $this->availability;
    }

    public function setAvailability(int $availability): Product
    {
        $this->availability = $availability;
        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): Product
    {
        $this->category = $category;
        return $this;
    }

    public function getPriceAtCoins(): int
    {
        return $this->price;
    }

    public function getPriceAtBills(): float
    {
        return $this->fromCoinsToBills($this->price);
    }

    public function setPriceAtCoins(int $price): Product
    {
        $this->price = $price;
        return $this;
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function setImage(Image $image): Product
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Convert array to Product
     * @param array $data
     */
    public function fromArray(array $data): void
    {
        $this->setId($data['id']);
        $this->setTitle($data['title']);
        $this->setDescription($data['description']);
        $this->setCategory($data['category']);
        $this->setAvailability($data['availability']);
        $this->setPriceAtCoins($data['price']);
        $this->setImage($data['image']);
        $this->setCreateAt($data['create_at']);
        $this->setUpdateAt($data['update_at']);
    }

    private function fromCoinsToBills(int $coins): float
    {
        return $coins / 100;
    }
}
