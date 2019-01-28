<?php

namespace Test;

use App\Extensions\UserAlreadyExistExtension;
use App\Models\User;
use App\Repository\ContactPersonRepository;
use App\Repository\UsersRepository;
use App\Services\UserService;
use Framework\Config;
use Framework\Database;
use Framework\HTTP\Request;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private $userService;
    private $contactPersonRepository;
    private $usersRepository;

    public function setUp()
    {
        Config::init('src/Config/');
        $dataBase = Database::getInstance();
        $dataBase->execute('delete from users;');
        $dataBase->execute('ALTER TABLE users AUTO_INCREMENT = 1;');
        $dataBase->execute('delete from contacts_persons');
        $dataBase->execute('ALTER TABLE contacts_persons AUTO_INCREMENT = 1;');

        $this->contactPersonRepository = new ContactPersonRepository();

        $this->usersRepository = new UsersRepository();

        $this->userService = new UserService($this->contactPersonRepository, $this->usersRepository);
    }

    public function testHashPassword()
    {
        $expected = 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f' .
            '2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db';

        $this->assertEquals($expected, $this->userService->hashPassword('1234'));
    }

    public function testUserServiceSave()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $request = Request::getInstance();

        $request->setPostData([
            'email' => 'test@email',
            'city' => 'city',
            'stock' => 'stock',
            'phone' => 'phone',
            'last-name' => 'lastName',
            'first-name' => 'firstName',
            'password' => '1234'
        ]);

        $user = $this->userService->create($request);

        $this->assertEquals(User::class, get_class($user));
        $this->assertEquals(1, $user->getId());
        $this->assertEquals('test@email', $user->getEmail());
        $this->assertEquals(0, $user->getIsAdmin());
        $this->assertEquals($this->userService->hashPassword('1234'), $user->getPassword());
        $this->assertEquals(1, $user->getContactPerson()->getId());
        $this->assertEquals('firstName', $user->getContactPerson()->getFirstName());
        $this->assertEquals('lastName', $user->getContactPerson()->getLastName());
        $this->assertEquals('phone', $user->getContactPerson()->getPhone());
        $this->assertEquals('stock', $user->getContactPerson()->getStock());
        $this->assertEquals('city', $user->getContactPerson()->getCity());

        $this->assertEquals(1, $this->usersRepository->findCount('users.id = :id', [
            'id' => $user->getId()
        ]));

        $this->assertEquals(1, $this->contactPersonRepository->findCount('contacts_persons.id = :id', [
            'id' => $user->getContactPerson()->getId()
        ]));
    }

    public function testUserServiceSaveAlreadyExistUser(){
        $this->expectException(UserAlreadyExistExtension::class);
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $request = Request::getInstance();

        $request->setPostData([
            'email' => 'test@email',
            'city' => 'city',
            'stock' => 'stock',
            'phone' => 'phone',
            'last-name' => 'lastName',
            'first-name' => 'firstName',
            'password' => '1234'
        ]);

        $this->userService->create($request);
        $this->userService->create($request);
    }
}
