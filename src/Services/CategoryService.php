<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Framework\Config;

class CategoryService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function calcPagesCount(int $categoryId): int
    {
        $count = $this->productRepository->findCountByCategoryId($categoryId);
        $countAtPage = Config::get('count_at_page');

        if ($count < $countAtPage) {
            return 1;
        }

        return ceil($count / $countAtPage);
    }

    public function getProductsByPage(int $categoryId, int $currentPage): array
    {
        $countAtPage = Config::get('count_at_page');
        $from = ($currentPage - 1) * $countAtPage;
        return $this->productRepository->findByCategoryIdWithLimit($categoryId, $from, $countAtPage);
    }

}