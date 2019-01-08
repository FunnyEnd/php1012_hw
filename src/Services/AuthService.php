<?php

namespace App\Services;

use App\Extensions\UserNotExistExtension;
use App\Repository\UsersRepository;
use Framework\Session;

class AuthService
{
    private const ID_COOKIE_KEY = 'user_id';

    private $session;
    private $usersRepository;
    private $userService;

    public function __construct(Session $session, UsersRepository $usersRepository, UserService $userService)
    {
        $this->session = $session;
        $this->usersRepository = $usersRepository;
        $this->userService = $userService;
    }

    /**
     * todo log UserNotExistExtension
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function auth(string $email, string $password): bool
    {
        try {
            $user = $this->usersRepository->findByEmail($email);
        } catch (UserNotExistExtension $e) {
            return false;
        }

        if ($user->getPassword() == $this->userService->hashPassword($password)) {
            $this->session->start();
            $this->session->set(self::ID_COOKIE_KEY, $user->getId());
            return true;
        }

        return false;
    }

    public function isAuth(): bool
    {
        if ($this->session->sessionExist() && $this->session->keyExist(self::ID_COOKIE_KEY))
            return true;

        return false;
    }

    public function isAdmin(): bool
    {
        if (!$this->isAuth())
            return false;

        try {
            $user = $this->usersRepository->findById($this->getUserId());
            if ($user->getIsAdmin())
                return true;

        } catch (UserNotExistExtension $e) {
            return false;
        }

        return false;
    }

    public function getUserId(): string
    {
        return $this->session->get(self::ID_COOKIE_KEY);
    }

    public function logOut(): void
    {
        $this->session->destroy();
    }
}