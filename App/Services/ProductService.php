<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function findById(int $id): array
    {
        return $this->productModel->getById($id);
    }

    public function findByCategoryId(int $categoryId): array
    {
        return $this->productModel->getByCatId($categoryId);
    }
}