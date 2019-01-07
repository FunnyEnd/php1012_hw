<?php

namespace App\Services;

use App\Repository\UsersRepository;

class RegisterService
{
    private $usersRepository;
    private $userService;
    private $authService;

    public function __construct(AuthService $authService, UsersRepository $usersRepository, UserService $userService)
    {
        $this->authService = $authService;
        $this->usersRepository = $usersRepository;
        $this->userService = $userService;
    }

    // todo
}