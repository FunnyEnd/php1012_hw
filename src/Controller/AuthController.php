<?php

namespace App\Controller;


use App\Repository\CategoryRepository;
use App\Services\AuthService;
use App\Services\UserService;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\View;

class AuthController extends BaseController
{
    private $categoryRepository;
    private $authService;
    private $userService;

    public function __construct(CategoryRepository $categoryRepository, AuthService $authService, UserService $userService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->authService = $authService;
        $this->userService = $userService;
    }

    public function index()
    {
        $category = $this->categoryRepository->findAll();
        $isAuth = $this->authService->isAuth();
        return View::render("auth", ["category" => $category, 'auth' => $isAuth]);
    }

    public function auth(Request $request)
    {
        $auth = $this->authService->auth(
                $request->post("email"),
                $request->post("password")
        );

        if (!$auth) {
            $category = $this->categoryRepository->findAll();
            return View::render("auth", [
                    "category" => $category,
                    "auth" => $auth,
                    "email" => $request->post("email")
            ]);
        }

        Response::redirect("/");
        return "";
    }

    public function logout()
    {
        $this->authService->logOut();
        Response::redirect("/");
        return "";
    }
}