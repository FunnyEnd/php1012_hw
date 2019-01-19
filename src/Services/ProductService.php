<?php

namespace App\Services;

use Framework\Config;

class ProductService
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getPopular()
    {
        $count = Config::get('count_at_home_page');
        return $this->categoryService->getProductsByPage(1, 1, $count);
    }
}
