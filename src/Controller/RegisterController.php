<?php

namespace App\Controller;

use App\Extensions\UserAlreadyExistExtension;
use App\Models\User;
use App\Repository\UsersRepository;
use App\Services\AuthService;
use App\Services\UserService;
use App\View\UserView;
use DateTime;
use Exception;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Zaine\Log;

class RegisterController extends BaseController
{
    private $userService;
    private $usersRepository;

    public function __construct(UserService $userService, UsersRepository $usersRepository)
    {
        $this->userService = $userService;
        $this->usersRepository = $usersRepository;
    }

    public function index()
    {
        return UserView::render('register', [
                'error' => '',
                'email' => '',
                'password' => '',
                'firstName' => '',
                'lastName' => ''
        ]);
    }

    public function register(Request $request, AuthService $authService, Log $logger)
    {
        try {
            $user = new User();
            $dateTime = new DateTime();
            $user->fromArray([
                    'id' => 0,
                    'email' => $request->post('email'),
                    'password' => $this->userService->hashPassword($request->post('password')),
                    'first_name' => $request->post('first-name'),
                    'last_name' => $request->post('last-name'),
                    'is_admin' => 0,
                    'create_at' => $dateTime,
                    'update_at' => $dateTime
            ]);

            $this->usersRepository->save($user);
        } catch (UserAlreadyExistExtension $e) {
            return UserView::render('register', [
                    'error' => 'User already exist',
                    'email' => $request->post('email'),
                    'password' => $request->post('password'),
                    'firstName' => $request->post('first-name'),
                    'lastName' => $request->post('last-name')
            ]);
        } catch (Exception $e){
            $logger->error($e->getMessage());
            return '';
        }

        $authService->auth($request->post('email'), $request->post('password'));

        Response::redirect('/');
        return '';
    }
}
