<?php

namespace App\Services\Category;


use Framework\HTTP\Request;

interface CategoryService
{
    public function getProducts(Request $request, $currentPage): array;

    public function getPagesCount(Request $request): int;

    public function getCurrentPage(Request $request): int;

    public function getPagination(Request $request): array;
}