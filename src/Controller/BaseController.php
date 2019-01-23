<?php
/**
 * Created by PhpStorm.
 * User: phpstudent
 * Date: 23.01.19
 * Time: 18:04
 */

namespace App\Controller;

use App\Services\AuthService;
use Framework\Controller;
use Framework\Dispatcher;

class BaseController extends Controller
{
    public function guard(string $guard): bool
    {
        switch ($guard) {
            case 'admin':
                $authService = Dispatcher::get(AuthService::class);
                return $authService->isAdmin();
            default:
                return false;
        }
    }
}
