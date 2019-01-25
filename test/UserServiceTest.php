<?php

namespace Test;

use App\Models\ContactPerson;
use App\Models\User;
use App\Repository\ContactPersonRepository;
use App\Repository\UsersRepository;
use App\Services\UserService;
use DateTime;
use Framework\HTTP\Request;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private $userService;

    public function setUp()
    {
        try {
            $mockContactPersonRepository = $this->getMockBuilder(ContactPersonRepository::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();

            $dateTime = new DateTime();

            $contactPerson = (new ContactPerson())
                ->setId(1)
                ->setEmail('test@email')
                ->setPhone('phone')
                ->setCity('city')
                ->setStock('stock')
                ->setLastName('lastName')
                ->setFirstName('firstName')
                ->setCreateAt($dateTime)
                ->setUpdateAt($dateTime);

            $mockContactPersonRepository->method('save')->willReturn($contactPerson);

            $mockUsersRepository = $this->getMockBuilder(UsersRepository::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();

            $user = (new User)
                ->setEmail('test@email')
                ->setIsAdmin(0)
                ->setPassword('d404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d' .
                    '04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db')
                ->setId(1)
                ->setContactPerson(new ContactPerson($contactPerson));

            $mockUsersRepository->method('save')->willReturn($user);

            $this->userService = new UserService($mockContactPersonRepository, $mockUsersRepository);

            parent::setUp();
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
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
        
        $this->assertEquals(1, $user->getId());
    }
}
