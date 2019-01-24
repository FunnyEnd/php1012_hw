<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Services\Admin\CategoryService;
use App\Services\AuthService;
use App\View\AdminView;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class CategoryController extends BaseController
{
    private $authService;
    private $categoryService;

    public function __construct(AuthService $authService, CategoryService $categoryService)
    {
        $this->authService = $authService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request): string
    {
        if (!$this->guard('admin')) {
            return Response::redirect('/auth');
        }

        $currentPage = 1;
        if ($request->issetGet('page')) {
            $currentPage = $request->get('page');
        }

        $countPages = $this->categoryService->getCountPages();
        $category = $this->categoryService->getCategory($currentPage);

        return AdminView::render('category', [
            'category' => $category,
            'currentPage' => $currentPage,
            'countPages' => $countPages
        ]);
    }

    public function store(Request $request)
    {
        if (!$this->guard('admin')) {
            return Response::redirect('/auth');
        }

        $this->categoryService->store($request);

        return Response::redirect('/admin/category');
    }

    public function update(Request $request){
        if (!$this->guard('admin')) {
            return Response::redirect('/auth');
        }

        $this->categoryService->update($request);

        return Response::redirect('/admin/category');
    }

    public function delete(Request $request){
        if (!$this->guard('admin')) {
            return Response::redirect('/auth');
        }

        $this->categoryService->delete($request);

        return Response::redirect('/admin/category');
    }

}