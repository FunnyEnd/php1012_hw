<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Repository\CategoryRepository;
use Framework\Config;
use Framework\HTTP\Request;

class CategoryService
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCountPages()
    {
        $countAtPage = Config::get('count_category_at_page');
        $count = $this->categoryRepository->findCount();

        if ($countAtPage > $count) {
            return 1;
        }

        return ceil($count / $countAtPage);
    }

    public function getCategory($currentPage)
    {
        $count = Config::get('count_category_at_page');
        $from = ($currentPage - 1) * $count;

        return $this->categoryRepository->findAll('', [
            'from' => $from,
            'count' => $count
        ]);
    }

    public function store(Request $request)
    {
        $this->categoryRepository->save((new Category())
            ->setTitle($request->fetch('post', 'title'))
        );
    }

    public function update(Request $request): Category
    {
        $category = $this->categoryRepository->findById($request->fetch('get', 'id'));

        if ($category->isEmpty()) {
            return new Category();
        }

        $category->setTitle($request->post('title'));
        return $this->categoryRepository->update(new Category($category));
    }

    public function delete(Request $request)
    {
        $category = $this->categoryRepository->findById($request->fetch('get', 'id'));
        $this->categoryRepository->delete(new Category($category));
    }
}