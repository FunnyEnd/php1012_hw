<?php

namespace App\Controller;

use App\Extensions\UserAlreadyExistExtension;
use App\Models\User;
use App\Repository\CategoryRepository;
use App\Repository\UsersRepository;
use App\Services\AuthService;
use App\Services\UserService;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\View;

class RegisterController extends BaseController
{
    private $categoryRepository;
    private $userService;
    private $usersRepository;
    private $authService;

    public function __construct(CategoryRepository $categoryRepository, UserService $userService,
                                UsersRepository $usersRepository, AuthService $authService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->userService = $userService;
        $this->usersRepository = $usersRepository;
        $this->authService = $authService;
    }

    public function index()
    {
        $category = $this->categoryRepository->findAll();
        $isAuth = $this->authService->isAuth();
        return View::render("register", [
                "category" => $category,
                "auth" => $isAuth,
                "error" => "",
                "email" => "",
                "password" => "",
                "firstName" => "",
                "lastName" => ""
        ]);
    }

    public function register(Request $request)
    {
        try {
            $user = new User();
            $user->setEmail($request->post("email"));
            $user->setPassword($this->userService->hashPassword($request->post("password")));
            $user->setFirstName($request->post("first-name"));
            $user->setLastName($request->post("last-name"));
            $user->setIsAdmin(false);
            $this->usersRepository->save($user);
        } catch (UserAlreadyExistExtension $e) {
            $category = $this->categoryRepository->findAll();
            $isAuth = $this->authService->isAuth();
            return View::render("register", [
                    "category" => $category,
                    "auth" => $isAuth,
                    "error" => "User already exist",
                    "email" => $request->post("email"),
                    "password" => $request->post("password"),
                    "firstName" => $request->post("first-name"),
                    "lastName" => $request->post("last-name")
            ]);
        }
        Response::redirect("/");
        return "";
    }
}
