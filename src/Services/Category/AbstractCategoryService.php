<?php

namespace App\Services\Category;

use Framework\HTTP\Request;

abstract class AbstractCategoryService implements CategoryService
{
    public function getCurrentPage(Request $request): int
    {
        $currentPage = 1;

        if ($request->issetGet('page')) {
            $currentPage = intval($request->get('page'));
        }

        return $currentPage;
    }

    public function getPagination(Request $request): array
    {
        $part2 = "";

        if ($request->issetGet('filter')) {
            $part2 = "/filter/" . $request->get('filter');
        }

        return [
                'part1' => "/category/{$request->get('id')}/page/",
                'part2' => $part2
        ];
    }
}