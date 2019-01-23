<?php

namespace App\Services;

use App\Extensions\UserNotExistExtension;
use App\Models\User;
use App\Repository\UsersRepository;
use Framework\Session;
use Zaine\Log;

class AuthService
{
    private const ID_COOKIE_KEY = 'user_id';

    private $session;
    private $usersRepository;
    private $userService;
    private $logger;

    public function __construct(Session $session, UsersRepository $usersRepository, UserService $userService)
    {
        $this->session = $session;
        $this->usersRepository = $usersRepository;
        $this->userService = $userService;
        $this->logger = new Log("AuthService");
    }

    public function auth(string $email, string $password): bool
    {
        try {
            $user = $this->usersRepository->findByEmail($email);
        } catch (UserNotExistExtension $e) {
            $this->logger->warning("User with email '$email' and password '$password' mot found.");
            return false;
        }

        if ($user->getPassword() == $this->userService->hashPassword($password)) {
            $this->session->start();
            $this->session->set(self::ID_COOKIE_KEY, $user->getId());
            return true;
        }

        return false;
    }

    public function isAdmin(): bool
    {
        if (!$this->isAuth()) {
            return false;
        }

        $user = $this->usersRepository->findById($this->getUserId());

        if ($user->isEmpty()) {
            $this->logger->warning("Can not found current user.");
            return false;
        }

        if ($user->getIsAdmin()) {
            return true;
        }
        return false;
    }

    public function isAuth(): bool
    {
        if ($this->session->sessionExist())
            if ($this->session->keyExist(self::ID_COOKIE_KEY))
                return true;

        return false;
    }

    public function getUserId(): string
    {
        return $this->session->get(self::ID_COOKIE_KEY);
    }

    public function getCurrentUser(): User
    {
        if ($this->isAuth()) {
            return (new User())->setId($this->getUserId());
        } else {
            return (new User())->setId(0);
        }
    }

    public function logOut(): void
    {
        $this->session->destroy();
    }
}