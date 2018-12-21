<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    public function findById(int $id): array
    {
        return $this->categoryModel->getById($id);
    }

    public function findAll(): array
    {
        return $this->categoryModel->getAll();
    }
}