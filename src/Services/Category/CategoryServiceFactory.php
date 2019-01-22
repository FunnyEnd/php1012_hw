<?php

namespace App\Services\Category;

use App\Services\FilterService;
use Framework\Dispatcher;
use Framework\HTTP\Request;

class CategoryServiceFactory
{
    public function getCategoryService(Request $request): CategoryService
    {
        $filterService = Dispatcher::get(FilterService::class);
        $filterSelectedValues = $filterService->getSelectedValues($request);

        if (!empty($filterSelectedValues)) {
            return Dispatcher::get(CategoryFilterService::class);
        }

        return Dispatcher::get(CategoryDefaultService::class);
    }
}