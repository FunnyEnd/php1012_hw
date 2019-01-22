<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Framework\Config;

class ProductService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getPopular()
    {
        return $this->productRepository->findAll('', [
                'from' => 1,
                'count' => Config::get('count_at_home_page')
        ]);
    }
}
