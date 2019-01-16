<?php

//namespace App\Services;
//
//use App\Extensions\UserAlreadyExistExtension;
//use App\Models\User;
//use App\Repository\UsersRepository;
//
//class RegisterService
//{
//    private $usersRepository;
//    private $userService;
//    private $authService;
//
//    public function __construct(AuthService $authService, UsersRepository $usersRepository, UserService $userService)
//    {
//        $this->authService = $authService;
//        $this->usersRepository = $usersRepository;
//        $this->userService = $userService;
//    }
//
//    public function register(User $user): bool
//    {
//        try {
//            $registeredUser = $this->usersRepository->save($user);
//            $this->authService->auth($registeredUser->getEmail(), $registeredUser->getPassword());
//        } catch (UserAlreadyExistExtension $e) {
//            return false;
//        }
//        return true;
//    }
//}