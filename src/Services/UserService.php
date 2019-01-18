<?php

namespace App\Services;

use App\Models\ContactPerson;
use App\Models\User;
use App\Repository\ContactPersonRepository;
use App\Repository\UsersRepository;
use Framework\HTTP\Request;

class UserService
{
    private $contactPersonRepository;
    private $usersRepository;
    private $userService;

    public function __construct(ContactPersonRepository $contactPersonRepository, UsersRepository $usersRepository)
    {
        $this->contactPersonRepository = $contactPersonRepository;
        $this->usersRepository = $usersRepository;
    }

    public function hashPassword(string $password): string
    {
        return hash("sha512", $password);
    }

    /**
     * @param Request $request
     * @return User
     * @throws \App\Extensions\UserAlreadyExistExtension
     */
    public function create(Request $request): User
    {
        $contactPerson = (new ContactPerson())
                ->setEmail($request->post('email'))
                ->setPhone($request->post('phone'))
                ->setFirstName($request->post('first-name'))
                ->setLastName($request->post('last-name'))
                ->setStock($request->post('stock'))
                ->setCity($request->post('city'));

        $contactPerson = $this->contactPersonRepository->save($contactPerson);

        $user = (new User())
                ->setEmail($request->post('email'))
                ->setPassword($this->hashPassword($request->post('password')))
                ->setIsAdmin(0)
                ->setContactPerson($contactPerson);

        return $this->usersRepository->save($user);
    }
}